<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use Mapper\XmlModelMapper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TTBooking\DirectBank\Fixture\PacketFixture;

final class PacketTest extends TestCase
{
    public function testXml(): void
    {
        $packet = PacketFixture::createPacket();

        $xml = $packet->toXml();

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_Packet.xsd'));
    }

    public function testStatementRequest()
    {
        $documentId = (string) Uuid::uuid4();
        $packetId = (string) Uuid::uuid4();
        $userAgent = '1C: TTBooking Client';
        $createDate = new \DateTimeImmutable();
        $dateFormat = \DATE_ATOM;//'Y-m-d\TH:i:s.000';

        $customer = (new CustomerPartyType())
            ->setId('40702810601300003716')
            ->setName('Коллегия адвокатов "Хренов и Партнеры"')
            ->setInn('7701336883');

        $bank = (new BankPartyType())
            ->setBic('044525593')
            ->setName('АО "АЛЬФА-БАНК"');

        $request = (new StatementRequest())
            ->setId($documentId)
            ->setCreationDate($createDate->format($dateFormat))
            ->setUserAgent($userAgent)
            ->setSender($customer)
            ->setRecipient($bank)
            ->setData(
                (new StatementRequestData())
                    ->setStatementType(0)
                    ->setDateFrom('2021-01-15T13:51:45.477')
                    ->setDateTo('2021-01-22T13:51:45.477')
                    ->setAccount('40702810601300003716')
                    ->setBank(
                        (new BankType())
                            ->setBic('044525593')
                    )
            );

        $xml = $request->toXml();

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_StatementRequest.xsd'));
    }

    /**
     * Официальный пример запроса выписки из описания стандарта 1С:
     * разбирается и собирается обратно в XML, проходящий XSD
     */
    public function testOfficialStatementRequest()
    {
        $request = (new XmlModelMapper())->map(
            file_get_contents(__DIR__ . '/../Fixture/xml/1c/StatementRequest.xml'),
            new StatementRequest()
        );

        $this->assertSame('da06dc8f-afbe-4172-89c8-0d4492c2dd25', $request->getId());
        $this->assertSame('7705260699', $request->getSender()->getInn());
        $this->assertSame('044525888', $request->getRecipient()->getBic());
        $this->assertSame('40702810500000000001', $request->getData()->getAccount());
        $this->assertSame('2016-04-21T00:00:00', $request->getData()->getDateFrom());

        $dom = new \DOMDocument();
        $dom->loadXML($request->toXml());

        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_StatementRequest.xsd'));
    }

    public function testMultipleDocuments()
    {
        $packet = PacketFixture::createPacket();
        $first = $packet->getDocument();
        $second = (new DocumentType())
            ->setId((string) Uuid::uuid4())
            ->setDockind($first->getDockind())
            ->setData($first->getData());

        $packet->addDocument($second);

        $dom = new \DOMDocument();
        $dom->loadXML($packet->toXml());
        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_Packet.xsd'));
        $this->assertSame(2, $dom->getElementsByTagName('Document')->length);

        $mapped = (new XmlModelMapper())->map($packet->toXml(), new Packet());

        $this->assertCount(2, $mapped->getDocuments());
        $this->assertSame($first->getId(), $mapped->getDocument()->getId());
        $this->assertSame($second->getId(), $mapped->getDocuments()[1]->getId());

        $mapped = (new XmlModelMapper())->map(PacketFixture::createPacket()->toXml(), new Packet());

        $this->assertCount(1, $mapped->getDocuments());
    }

    public function testWithoutUserAgent()
    {
        $packet = PacketFixture::createPacket();

        $this->assertNull((new Packet())->getUserAgent());
        $this->assertNull((new StatementRequest())->getUserAgent());
        $this->assertFalse($packet->getDocument()->isTestOnly());
        $this->assertFalse($packet->getDocument()->isCompressed());
    }
}
