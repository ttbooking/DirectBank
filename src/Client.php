<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;


use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TTBooking\DirectBank\Dictionary\DefaultValue;
use TTBooking\DirectBank\Dictionary\ErrorCode;
use GuzzleHttp\Client as HttpClient;
use TTBooking\DirectBank\Exceptions\ClientException;
use TTBooking\DirectBank\Exceptions\InvalidSettingsException;
use TTBooking\DirectBank\Exceptions\UnexpectedResponseException;
use TTBooking\DirectBank\Objects\Packet;
use TTBooking\DirectBank\Objects\ResultBank;

class Client implements ClientInterface
{
    protected array $settings = [
        'customerId' => null,
        'apiVersion' => DefaultValue::FORMAT_VERSION,
        // Максимальная версия API, которую поддерживает клиент (заголовок AvailableAPIVersion в Logon)
        'availableApiVersion' => DefaultValue::FORMAT_VERSION,
        'userAgent' => null,
        'sessionId' => null,
        'login' => null,
        'password' => null,
        'url' => null,
    ];

    public function __construct(array $settings, private ?LoggerInterface $logger = null)
    {
        $this->settings = array_replace($this->settings, $settings);

        $this->validateSettings($this->settings);
    }

    public function createSession(): string
    {
        $result = $this->invoke('POST', 'Logon');

        $response = $result->getSuccess()->getLogonResponse()
            ?? throw new UnexpectedResponseException('Bank response to Logon has no LogonResponse.');

        return $response->getSID();
    }

    public function sendPack(Packet $packet): string
    {
        $result = $this->invoke('POST', 'SendPack', (string) $packet);

        $response = $result->getSuccess()->getSendPacketResponse()
            ?? throw new UnexpectedResponseException('Bank response to SendPack has no SendPacketResponse.');

        return $response->getID();
    }

    /**
     * @param \DateTimeInterface|null $dateTime отметка времени по часам сервера банка
     */
    public function getPackList(?\DateTimeInterface $dateTime = null): ?array
    {
        $result = $this->invoke('GET', 'GetPackList', query: ['date' => $dateTime?->format(DefaultValue::TIMESTAMP_FORMAT)]);

        return $result->getSuccess()->getGetPacketListResponse()?->getPacketID();
    }

    public function getPack(string $uid): Packet
    {
        $result = $this->invoke('GET', 'GetPack', query: ['id' => $uid]);

        return $result->getSuccess()->getGetPacketResponse()
            ?? throw new UnexpectedResponseException('Bank response to GetPack has no GetPacketResponse.');
    }

    /**
     * @throws \TTBooking\DirectBank\Exceptions\InvalidSettingsException
     */
    protected function validateSettings(array $settings): void
    {
        foreach (['url', 'customerId', 'login', 'password', 'apiVersion'] as $key) {
            if (! is_string($settings[$key]) || $settings[$key] === '') {
                throw new InvalidSettingsException(sprintf('Setting "%s" is required and must be a non-empty string.', $key));
            }
        }

        foreach (['sessionId', 'availableApiVersion', 'userAgent'] as $key) {
            if (! is_null($settings[$key]) && (! is_string($settings[$key]) || $settings[$key] === '')) {
                throw new InvalidSettingsException(sprintf('Setting "%s" must be a non-empty string or null.', $key));
            }
        }

        if (isset($settings['verify']) && ! is_bool($settings['verify']) && ! is_string($settings['verify'])) {
            throw new InvalidSettingsException('Setting "verify" must be a boolean or a path to a CA bundle.');
        }

        if (isset($settings['handler']) && ! is_callable($settings['handler'])) {
            throw new InvalidSettingsException('Setting "handler" must be a Guzzle handler (callable).');
        }
    }

    /**
     * @return ResultBank с заполненным Success
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TTBooking\DirectBank\Exceptions\ClientException
     */
    protected function invoke(string $method, string $path, ?string $body = null, array $query = [], bool $reauthenticate = true): ResultBank
    {
        $client = $this->getHttpClient($this->settings, $path !== 'Logon');

        $response = $client->request($method, $path, ['body' => $body, 'query' => $query]);

        $result = $this->parseResult($response);

        if ($error = $result?->getError()) {
            // Сессия истекла или недействительна: входим заново и повторяем запрос один раз
            if ($reauthenticate && $path !== 'Logon' && in_array($error->getCode(), ErrorCode::REAUTHENTICATE, true)) {
                $this->settings['sessionId'] = null;

                return $this->invoke($method, $path, $body, $query, false);
            }

            throw ClientException::fromError($error);
        }

        if ($response->getStatusCode() >= 400) {
            throw UnexpectedResponseException::fromResponse(
                sprintf('Bank responded with HTTP %d %s and no error description.', $response->getStatusCode(), $response->getReasonPhrase()),
                $response
            );
        }

        if (! $result?->getSuccess()) {
            throw UnexpectedResponseException::fromResponse(sprintf('Bank response to %s has neither Success nor Error.', $path), $response);
        }

        return $result;
    }

    /**
     * Ошибка банка приходит в теле ResultBank при любом HTTP-статусе,
     * поэтому тело разбирается до проверки статуса.
     *
     * @throws \TTBooking\DirectBank\Exceptions\UnexpectedResponseException
     */
    protected function parseResult(ResponseInterface $response): ?ResultBank
    {
        $body = (string) $response->getBody();

        if (trim($body) === '') {
            return null;
        }

        try {
            $result = new ResultBank();
            $result->mapFromXml($body);
        } catch (\Throwable $e) {
            if ($response->getStatusCode() >= 400) {
                return null;
            }

            throw UnexpectedResponseException::fromResponse('Unable to parse bank response: ' . $e->getMessage(), $response, $e);
        }

        return $result;
    }

    /**
     * @param bool $withSession запрос в рамках сессии (SID); иначе запрос аутентификации (Logon)
     */
    protected function getHttpClient(array $settings, $withSession = false): HttpClient
    {
        $handler = $this->settings['handler'] ?? new CurlHandler();
        $stack = HandlerStack::create($handler);

        if ($this->logger) {
            $stack->push(Middleware::log($this->logger, new MessageFormatter(MessageFormatter::DEBUG)));
        }

        if ($withSession) {
            $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
                if(! $request->hasHeader('sid')) {
                    $sessionId = $this->createSession();
                    $this->settings['sessionId'] = $sessionId;

                    return $request->withHeader('sid', $sessionId);
                }
                return $request;
            }));
        }

        $headers = [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Accept' => 'application/xml',
            'customerid' => $settings['customerId'],
            'apiversion' => $settings['apiVersion'],
        ];

        if (isset($settings['userAgent'])) {
            $headers['User-Agent'] = $settings['userAgent'];
        }

        $config = [
            'base_uri' => $settings['url'],
            'verify' => $this->settings['verify'] ?? true,
            'http_errors' => false,
            'handler' => $stack,
        ];

        // Логин и пароль передаются только при аутентификации, дальше запросы идут с SID
        if ($withSession) {
            if ($settings['sessionId'] ?? null) {
                $headers['sid'] = $settings['sessionId'];
            }
        } else {
            if (isset($settings['availableApiVersion'])) {
                $headers['availableapiversion'] = $settings['availableApiVersion'];
            }

            $config['auth'] = [$settings['login'], $settings['password']];
        }

        return new HttpClient(['headers' => $headers] + $config);
    }
}