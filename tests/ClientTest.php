<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;

use Mapper\XmlModelMapper;
use PHPUnit\Framework\TestCase;
use TTBooking\DirectBank\Dictionary\DefaultValue;
use TTBooking\DirectBank\Dictionary\DocKind;
use TTBooking\DirectBank\Fixture\PacketFixture;
use TTBooking\DirectBank\Objects\Statement;
use TTBooking\DirectBank\Objects\StatusPacketNotice;

final class ClientTest extends TestCase
{
    protected array $settings = [
        'customerId' => '40702810601300003716',
        'apiVersion' => DefaultValue::FORMAT_VERSION,
        'sessionId' => null,
        'login' => 'directBankTestUser',
        'password' => '123456',
        'url' => 'https://grampus-int.alfabank.ru/API/v1/directbank/',
        'verify' => false,
    ];

    protected ClientInterface $client;

    public function setUp(): void
    {
        $this->client = new Client($this->settings);
    }

    public function testSession()
    {
        $this->assertIsString($this->client->createSession());
    }

    public function testSendPack()
    {
        $packet = PacketFixture::createPacket();

        $this->assertIsString($this->client->sendPack($packet));
    }

    public function testGetPackList()
    {
        $packet = PacketFixture::createPacket();

        $this->client->sendPack($packet);

        $this->assertIsArray($this->client->getPackList());
    }

    public function testGetPack()
    {
        $packIds = $this->client->getPackList();

        $this->assertIsArray($packIds);

        $xmlMapper = new XmlModelMapper();

        if($packIds) {
            foreach ($packIds as $packId) {
                $pack = $this->client->getPack($packId);

                $document = base64_decode($pack->getDocument()->getData());
                $docKind = $pack->getDocument()->getDockind();

                $document = match (true) {
                    $docKind === DocKind::SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION =>  $xmlMapper->map($document, new StatusPacketNotice()),
                    $docKind === DocKind::BANK_STATEMENT => $xmlMapper->map($document, new Statement()),
                    default => null,
                };
            }
        }
    }
}
