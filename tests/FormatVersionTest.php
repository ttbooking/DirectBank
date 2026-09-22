<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mapper\XmlModelMapper;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use TTBooking\DirectBank\Fixture\PacketFixture;
use TTBooking\DirectBank\Objects\{BankPartyType, CustomerPartyType, Packet, Probe, Statement};

final class FormatVersionTest extends TestCase
{
    protected function tearDown(): void
    {
        FormatVersion::setDefault(FormatVersion::LATEST);
    }

    protected function assertValid(string $xml, string $schema, string $version): void
    {
        $dir = __DIR__ . '/Fixture/xsd/' . ($version === FormatVersion::LATEST ? '' : "$version/");
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue($dom->schemaValidate($dir . '1C-Bank_' . $schema . '.xsd'));
    }

    protected static function probe(): Probe
    {
        return (new Probe())
            ->setId('eafa28b5-6600-424c-b0d3-3785274d570d')
            ->setCreationDate('2026-09-23T10:00:00')
            ->setSender((new CustomerPartyType())->setId('2806'))
            ->setRecipient((new BankPartyType())->setBic('044525888'));
    }

    public function testLatestByDefault()
    {
        $this->assertSame('2.3.2', FormatVersion::getDefault());

        $packet = PacketFixture::createPacket();

        $this->assertSame('2.3.2', $packet->getFormatVersion());
        $this->assertSame('2.3.2', $packet->getDocument()->getFormatVersion());
        $this->assertSame('2.3.2', self::probe()->getFormatVersion());
        $this->assertValid((string) $packet, 'Packet', '2.3.2');
    }

    public function versionProvider(): array
    {
        return [['2.2.2'], ['2.3.2']];
    }

    /**
     * Документы, собранные в каждой поддерживаемой версии, проходят схемы этой версии
     *
     * @dataProvider versionProvider
     */
    public function testDocumentsMatchVersionSchemas(string $version)
    {
        FormatVersion::setDefault($version);

        $packet = PacketFixture::createPacket();
        $probe = self::probe();

        $this->assertSame($version, $packet->getFormatVersion());
        $this->assertSame($version, $probe->getFormatVersion());
        $this->assertValid((string) $packet, 'Packet', $version);
        $this->assertValid(base64_decode($packet->getDocument()->getData()), 'StatementRequest', $version);
        $this->assertValid((string) $probe, 'Probe', $version);
    }

    public function testClientHeaders()
    {
        FormatVersion::setDefault('2.2.2');

        $mock = new MockHandler([new Response(200, [], '<ResultBank xmlns="http://directbank.1c.ru/XMLSchema" formatVersion="2.2.2"><Success><LogonResponse><SID>S</SID></LogonResponse></Success></ResultBank>')]);
        $requests = [];
        $handler = function (RequestInterface $request, array $options) use ($mock, &$requests) {
            $requests[] = $request;
            return $mock($request, $options);
        };

        (new Client([
            'url' => 'https://bank.example.ru/', 'customerId' => '1', 'login' => 'user', 'password' => 'secret', 'handler' => $handler,
        ]))->createSession();

        $this->assertSame('2.2.2', $requests[0]->getHeaderLine('APIVersion'));
        $this->assertSame('2.3.2', $requests[0]->getHeaderLine('AvailableAPIVersion'));
    }

    public function testParsedDocumentKeepsVersion()
    {
        $packet = (new XmlModelMapper())->map(file_get_contents(__DIR__ . '/Fixture/xml/1c/Packet.xml'), new Packet());
        $this->assertSame('2.2.1', $packet->getFormatVersion());

        $statement = new Statement();
        $statement->mapFromXml(file_get_contents(__DIR__ . '/Fixture/xml/1c/Statement.xml'));
        $this->assertSame('2.3.1', $statement->getFormatVersion());
    }

    public function testUnsupportedVersion()
    {
        $this->expectException(\InvalidArgumentException::class);

        FormatVersion::setDefault('2.1.1');
    }
}
