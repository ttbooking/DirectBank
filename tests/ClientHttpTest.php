<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use TTBooking\DirectBank\Exceptions\ClientException;
use TTBooking\DirectBank\Exceptions\UnexpectedResponseException;
use TTBooking\DirectBank\Fixture\PacketFixture;

final class ClientHttpTest extends TestCase
{
    protected array $settings = [
        'url' => 'https://bank.example.ru/API/v1/directbank/',
        'customerId' => '40702810000000000000',
        'login' => 'user',
        'password' => 'secret',
    ];

    protected MockHandler $mock;

    /**
     * @var RequestInterface[]
     */
    protected array $requests = [];

    protected function setUp(): void
    {
        $this->mock = new MockHandler();
        $this->requests = [];
    }

    protected function createClient(array $settings = []): Client
    {
        $handler = function (RequestInterface $request, array $options) {
            $this->requests[] = $request;

            return ($this->mock)($request, $options);
        };

        return new Client(['handler' => $handler] + $settings + $this->settings);
    }

    protected static function success(string $content): Response
    {
        return new Response(200, ['Content-Type' => 'application/xml; charset=utf-8'], self::resultBank("<Success>$content</Success>"));
    }

    protected static function error(string $code, string $description, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/xml; charset=utf-8'], self::resultBank(
            "<Error><Code>$code</Code><Description>$description</Description></Error>"
        ));
    }

    protected static function resultBank(string $content): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            . '<ResultBank xmlns="http://directbank.1c.ru/XMLSchema" formatVersion="2.2.2">' . $content . '</ResultBank>';
    }

    protected static function logon(string $sid = 'SID-1'): Response
    {
        return self::success("<LogonResponse><SID>$sid</SID></LogonResponse>");
    }

    public function testCreateSession()
    {
        $this->mock->append(self::logon());

        $this->assertSame('SID-1', $this->createClient()->createSession());

        $request = $this->requests[0];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/API/v1/directbank/Logon', $request->getUri()->getPath());
        $this->assertSame('Basic ' . base64_encode('user:secret'), $request->getHeaderLine('Authorization'));
        $this->assertSame('40702810000000000000', $request->getHeaderLine('customerid'));
        $this->assertSame('2.2.2', $request->getHeaderLine('apiversion'));
        $this->assertFalse($request->hasHeader('sid'));
    }

    public function testHeaders()
    {
        $this->mock->append(self::logon(), self::success('<SendPacketResponse><ID>PACK-1</ID></SendPacketResponse>'));

        $this->createClient(['sessionId' => 'SID-0'])->createSession();
        $this->createClient(['sessionId' => 'SID-0'])->sendPack(PacketFixture::createPacket());

        [$logon, $sendPack] = $this->requests;

        foreach ([$logon, $sendPack] as $request) {
            $this->assertSame('application/xml; charset=utf-8', $request->getHeaderLine('Content-Type'));
            $this->assertSame('40702810000000000000', $request->getHeaderLine('CustomerID'));
            $this->assertSame('2.2.2', $request->getHeaderLine('APIVersion'));
            $this->assertStringStartsWith('GuzzleHttp/', $request->getHeaderLine('User-Agent'));
        }

        // Logon: логин и пароль, доступная версия API, без SID
        $this->assertSame('Basic ' . base64_encode('user:secret'), $logon->getHeaderLine('Authorization'));
        $this->assertSame('2.2.2', $logon->getHeaderLine('AvailableAPIVersion'));
        $this->assertFalse($logon->hasHeader('SID'));

        // Запросы в рамках сессии: SID, без логина и пароля
        $this->assertSame('SID-0', $sendPack->getHeaderLine('SID'));
        $this->assertFalse($sendPack->hasHeader('Authorization'));
        $this->assertFalse($sendPack->hasHeader('AvailableAPIVersion'));
    }

    public function testHeadersFromSettings()
    {
        $this->mock->append(self::logon());

        $this->createClient([
            'apiVersion' => '2.1.1',
            'availableApiVersion' => null,
            'userAgent' => '1C+Enterprise/8.3',
        ])->createSession();

        $this->assertSame('2.1.1', $this->requests[0]->getHeaderLine('APIVersion'));
        $this->assertFalse($this->requests[0]->hasHeader('AvailableAPIVersion'));
        $this->assertSame('1C+Enterprise/8.3', $this->requests[0]->getHeaderLine('User-Agent'));
    }

    public function testLogonBeforeFirstRequest()
    {
        $this->mock->append(
            self::logon(),
            self::success('<SendPacketResponse><ID>PACK-1</ID></SendPacketResponse>'),
            self::success('<GetPacketListResponse TimeStampLastPacket="2026-09-22T10:00:00"><PacketID>A</PacketID></GetPacketListResponse>'),
        );

        $client = $this->createClient();
        $client->sendPack(PacketFixture::createPacket());
        $client->getPackList();

        $this->assertCount(3, $this->requests);
        $this->assertStringEndsWith('/Logon', $this->requests[0]->getUri()->getPath());
        $this->assertSame('SID-1', $this->requests[1]->getHeaderLine('sid'));
        $this->assertSame('SID-1', $this->requests[2]->getHeaderLine('sid'));
    }

    public function testSessionIdFromSettings()
    {
        $this->mock->append(self::success('<GetPacketListResponse TimeStampLastPacket="2026-09-22T10:00:00"><PacketID>A</PacketID></GetPacketListResponse>'));

        $this->createClient(['sessionId' => 'SID-0'])->getPackList();

        $this->assertCount(1, $this->requests);
        $this->assertSame('SID-0', $this->requests[0]->getHeaderLine('sid'));
    }

    public function testSendPack()
    {
        $this->mock->append(self::success('<SendPacketResponse><ID>PACK-1</ID></SendPacketResponse>'));

        $packet = PacketFixture::createPacket();

        $this->assertSame('PACK-1', $this->createClient(['sessionId' => 'SID-0'])->sendPack($packet));

        $request = $this->requests[0];
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringEndsWith('/SendPack', $request->getUri()->getPath());
        $this->assertSame((string) $packet, (string) $request->getBody());
    }

    public function testGetPackList()
    {
        $this->mock->append(
            self::success('<GetPacketListResponse TimeStampLastPacket="2026-09-22T10:00:00"><PacketID>A</PacketID><PacketID>B</PacketID></GetPacketListResponse>'),
            self::success('<GetPacketListResponse TimeStampLastPacket="2026-09-22T10:00:00"><PacketID>A</PacketID></GetPacketListResponse>'),
        );

        $client = $this->createClient(['sessionId' => 'SID-0']);

        $this->assertSame(['A', 'B'], $client->getPackList());
        $this->assertSame(['A'], $client->getPackList());

        $request = $this->requests[0];
        $this->assertSame('GET', $request->getMethod());
        $this->assertStringEndsWith('/GetPackList', $request->getUri()->getPath());
        $this->assertSame('', $request->getUri()->getQuery());
    }

    public function testGetPackListWithoutTimeStamp()
    {
        $this->mock->append(
            self::success('<GetPacketListResponse><PacketID>A</PacketID></GetPacketListResponse>'),
            self::success('<GetPacketListResponse/>'),
        );

        $client = $this->createClient(['sessionId' => 'SID-0']);

        $this->assertSame(['A'], $client->getPackList());
        $this->assertNull($client->getPackList());
    }

    public function testGetPackListDate()
    {
        $this->mock->append(self::success('<GetPacketListResponse TimeStampLastPacket="2026-09-22T10:00:00"><PacketID>A</PacketID></GetPacketListResponse>'));

        $this->createClient(['sessionId' => 'SID-0'])->getPackList(new \DateTimeImmutable('2015-02-16 11:25:32'));

        $query = [];
        parse_str($this->requests[0]->getUri()->getQuery(), $query);

        $this->assertSame(['date' => '16.02.2015 11:25:32'], $query);
    }

    public function testGetPack()
    {
        $packet = PacketFixture::createPacket();
        $xml = preg_replace('/^<\?xml[^>]*\?>\s*/', '', (string) $packet);
        $xml = str_replace(['<Packet xmlns="http://directbank.1c.ru/XMLSchema"', '</Packet>'], ['<GetPacketResponse', '</GetPacketResponse>'], $xml);

        $this->mock->append(self::success($xml));

        $result = $this->createClient(['sessionId' => 'SID-0'])->getPack($packet->getId());

        $this->assertSame($packet->getId(), $result->getId());
        $this->assertSame($packet->getDocument()->getData(), $result->getDocument()->getData());

        $request = $this->requests[0];
        $this->assertStringEndsWith('/GetPack', $request->getUri()->getPath());
        $this->assertSame('id=' . $packet->getId(), $request->getUri()->getQuery());
    }

    public function testBankError()
    {
        $this->mock->append(self::error('1201', 'Некорректные данные для аутентификации'));

        try {
            $this->createClient()->createSession();
            $this->fail('ClientException expected');
        } catch (ClientException $e) {
            $this->assertSame(1201, $e->getCode());
            $this->assertSame('1201', $e->getBankCode());
            $this->assertSame('Некорректные данные для аутентификации', $e->getMessage());
            $this->assertSame('Некорректные данные для аутентификации', $e->getError()->getDescription());
        }
    }

    public function testBankErrorCodeIsString()
    {
        $this->mock->append(self::error('0042', 'Код с ведущими нулями'), self::error('AB12', 'Буквенный код'));

        $client = $this->createClient();

        foreach (['0042' => 42, 'AB12' => 0] as $bankCode => $code) {
            try {
                $client->createSession();
                $this->fail('ClientException expected');
            } catch (ClientException $e) {
                $this->assertSame((string) $bankCode, $e->getBankCode());
                $this->assertSame($code, $e->getCode());
            }
        }
    }

    public function sessionErrorProvider(): array
    {
        return [
            'session timeout' => ['1006', 200],
            'invalid session id' => ['1007', 200],
            'invalid session id, HTTP 401' => ['1007', 401],
        ];
    }

    /**
     * @dataProvider sessionErrorProvider
     */
    public function testReauthenticateOnSessionError(string $code, int $status)
    {
        $this->mock->append(
            self::error($code, 'Сессия недействительна', $status),
            self::logon('SID-2'),
            self::success('<SendPacketResponse><ID>PACK-1</ID></SendPacketResponse>'),
            self::success('<SendPacketResponse><ID>PACK-2</ID></SendPacketResponse>'),
        );

        $client = $this->createClient(['sessionId' => 'SID-1']);
        $packet = PacketFixture::createPacket();

        $this->assertSame('PACK-1', $client->sendPack($packet));
        $this->assertSame('PACK-2', $client->sendPack($packet));

        $this->assertCount(4, $this->requests);
        $this->assertSame('SID-1', $this->requests[0]->getHeaderLine('sid'));
        $this->assertStringEndsWith('/Logon', $this->requests[1]->getUri()->getPath());
        $this->assertSame('SID-2', $this->requests[2]->getHeaderLine('sid'));
        $this->assertSame((string) $packet, (string) $this->requests[2]->getBody());
        $this->assertSame('SID-2', $this->requests[3]->getHeaderLine('sid'));
    }

    public function testReauthenticateOnlyOnce()
    {
        $this->mock->append(
            self::error('1006', 'Сессия закрыта по тайм-ауту'),
            self::logon('SID-2'),
            self::error('1006', 'Сессия закрыта по тайм-ауту'),
        );

        try {
            $this->createClient(['sessionId' => 'SID-1'])->getPackList();
            $this->fail('ClientException expected');
        } catch (ClientException $e) {
            $this->assertSame('1006', $e->getBankCode());
        }

        $this->assertCount(3, $this->requests);
    }

    public function testNoReauthenticateOnOtherErrors()
    {
        $this->mock->append(self::error('2102', 'Транспортный контейнер не найден'));

        try {
            $this->createClient(['sessionId' => 'SID-1'])->getPack('unknown');
            $this->fail('ClientException expected');
        } catch (ClientException $e) {
            $this->assertSame('2102', $e->getBankCode());
        }

        $this->assertCount(1, $this->requests);
    }

    public function testBankErrorWithHttpErrorStatus()
    {
        $this->mock->append(self::error('1009', 'Ошибка приемного сервиса', 500));

        try {
            $this->createClient()->createSession();
            $this->fail('ClientException expected');
        } catch (ClientException $e) {
            $this->assertNotInstanceOf(UnexpectedResponseException::class, $e);
            $this->assertSame('1009', $e->getBankCode());
        }
    }

    public function unexpectedResponseProvider(): array
    {
        return [
            'HTTP error without ResultBank' => [new Response(502, [], '<html>Bad Gateway</html>'), 502],
            'HTTP error with empty body' => [new Response(503), 503],
            'not XML' => [new Response(200, [], 'not xml'), 200],
            'empty body' => [new Response(200), 200],
            'ResultBank without Success and Error' => [new Response(200, [], self::resultBank('')), 200],
            'Success without LogonResponse' => [self::success('<SendPacketResponse><ID>PACK-1</ID></SendPacketResponse>'), 0],
        ];
    }

    /**
     * @dataProvider unexpectedResponseProvider
     */
    public function testUnexpectedResponse(Response $response, int $code)
    {
        $this->mock->append($response);

        try {
            $this->createClient()->createSession();
            $this->fail('UnexpectedResponseException expected');
        } catch (UnexpectedResponseException $e) {
            $this->assertSame($code, $e->getCode());
            $this->assertNull($e->getBankCode());
        }
    }

    public function testLogger()
    {
        $this->mock->append(self::logon());

        $logger = new \Monolog\Logger('test', [$handler = new \Monolog\Handler\TestHandler()]);

        (new Client(['handler' => $this->mock] + $this->settings, $logger))->createSession();

        $this->assertTrue($handler->hasInfoThatContains('Logon'));
    }
}
