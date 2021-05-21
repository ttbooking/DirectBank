<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

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
}
