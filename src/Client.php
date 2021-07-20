<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;


use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use TTBooking\DirectBank\Dictionary\DefaultValue;
use GuzzleHttp\Client as HttpClient;
use TTBooking\DirectBank\Exceptions\ClientException;
use TTBooking\DirectBank\Objects\Packet;
use TTBooking\DirectBank\Objects\ResultBank;

class Client implements ClientInterface
{
    protected array $settings = [
        'customerId' => null,
        'apiVersion' => DefaultValue::FORMAT_VERSION,
        'sessionId' => null,
        'login' => null,
        'password' => null,
        'url' => null,
    ];

    public function __construct(array $settings, private ?LoggerInterface $logger = null)
    {
        $this->settings = array_replace($this->settings, $settings);
    }

    public function createSession(): string
    {
        $result = $this->invoke('POST', 'Logon');

        return $result->getSuccess()->getLogonResponse()->getSID();
    }

    public function sendPack(Packet $packet): string
    {
        $result = $this->invoke('POST', 'SendPack', (string) $packet);

        return $result->getSuccess()->getSendPacketResponse()->getID();
    }

    public function getPackList(\DateTimeInterface $dateTime = null): ?array
    {
        $result = $this->invoke('GET', 'GetPackList', query: ['date' => $dateTime ? $dateTime->format('Y-m-d H:i:s') : null]);

        return $result->getSuccess()->getGetPacketListResponse()?->getPacketID();
    }

    public function getPack(string $uid): Packet
    {
        $result = $this->invoke('GET', 'GetPack', query: ['id' => $uid]);

        return $result->getSuccess()->getGetPacketResponse();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TTBooking\DirectBank\Exceptions\ClientException
     */
    protected function invoke(string $method, string $path, string $body = null, array $query = []): ResultBank
    {
        $client = $this->getHttpClient($this->settings, $path !== 'Logon');

        $response = $client->request($method, $path, ['body' => $body, 'query' => $query]);

        $result = new ResultBank();
        $result->mapFromXml((string) $response->getBody());

        if($error = $result->getError()) {
            throw new ClientException($error->getDescription(), $error->getCode());
        }

        return $result;
    }

    protected function getHttpClient(array $settings, $withAuth = false): HttpClient
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);

        if ($this->logger) {
            $stack->push(Middleware::log($this->logger, new MessageFormatter(MessageFormatter::DEBUG)));
        }

        if ($withAuth) {
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
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml',
            'customerid' => $settings['customerId'],
            'apiversion' => $settings['apiVersion'],
        ];

        if ($settings['sessionId'] ?? null) {
            $headers['sid'] = $settings['sessionId'];
        }

        return new HttpClient([
            'base_uri' => $settings['url'],
            'headers' => $headers,
            'auth' => [
                $settings['login'],
                $settings['password'],
            ],
            'verify' => $this->settings['verify'] ?? true,
            'handler' => $stack,
        ]);
    }
}