<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Fixture;


use Ramsey\Uuid\Uuid;
use TTBooking\DirectBank\Dictionary\DocKind;
use TTBooking\DirectBank\Objects\BankPartyType;
use TTBooking\DirectBank\Objects\BankType;
use TTBooking\DirectBank\Objects\CustomerPartyType;
use TTBooking\DirectBank\Objects\DocumentType;
use TTBooking\DirectBank\Objects\Packet;
use TTBooking\DirectBank\Objects\ParticipantType;
use TTBooking\DirectBank\Objects\StatementRequest;
use TTBooking\DirectBank\Objects\StatementRequestData;

class PacketFixture
{
    static public function createPacket(): Packet
    {
        $documentId = (string) Uuid::uuid4();
        $packetId = (string) Uuid::uuid4();
        $userAgent = '1C: TTBooking Client';
        $createDate = new \DateTimeImmutable();
        $dateFormat = \DATE_ATOM;

        $dateFrom = new \DateTimeImmutable('2021-01-15T00:00:00Z');
        $dateTo = new \DateTimeImmutable('2021-01-22T00:00:00Z');

        $customer = (new CustomerPartyType())
            ->setId('40702810601300003716');

        $bank = (new BankPartyType())
            ->setBic('044525593');

        $packet = (new Packet())
            ->setId($packetId)
            ->setUserAgent($userAgent)
            ->setCreationDate($createDate->format($dateFormat))
            ->setSender(
                (new ParticipantType())
                    ->setCustomer($customer)
            )
            ->setRecipient(
                (new ParticipantType())
                    ->setBank($bank)
            )
            ->setDocument(
                (new DocumentType())
                    ->setId($documentId)
                    ->setDockind(DocKind::BANK_STATEMENT_REQUEST)
                    ->setData(base64_encode(
                        (string) (new StatementRequest())
                            ->setId($documentId)
                            ->setCreationDate($createDate->format($dateFormat))
                            ->setUserAgent($userAgent)
                            ->setSender($customer)
                            ->setRecipient($bank)
                            ->setData(
                                (new StatementRequestData())
                                    ->setStatementType(0)
                                    ->setDateFrom($dateFrom->format($dateFormat))
                                    ->setDateTo($dateTo->format($dateFormat))
                                    ->setAccount('40702810601300003716')
                                    ->setBank(
                                        (new BankType())
                                            ->setBic('044525593')
                                    )
                            )
                    ))
            )
        ;

        return $packet;
    }
}