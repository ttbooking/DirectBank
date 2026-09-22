<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use Mapper\XmlModelMapper;
use PHPUnit\Framework\TestCase;
use TTBooking\DirectBank\Dictionary\DocStatus;
use TTBooking\DirectBank\Dictionary\PacketStatus;

/**
 * Служебные документы обмена: извещения, запрос о состоянии, запрос-зонд, настройки
 */
final class ServiceDocumentsTest extends TestCase
{
    protected static function fixture(string $name): string
    {
        return file_get_contents(__DIR__ . '/../Fixture/xml/' . $name);
    }

    protected function assertValid(string $xml, string $schema): void
    {
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_' . $schema . '.xsd'));
    }

    protected static function customer(): CustomerPartyType
    {
        return (new CustomerPartyType())->setId('id:42;s:9999')->setName('Торговый дом Комплексный')->setInn('7705260699')->setKpp('770501001');
    }

    protected static function bank(): BankPartyType
    {
        return (new BankPartyType())->setBic('044525888')->setName('ДЕМО-БАНК');
    }

    public function testStatusPacketNotice()
    {
        $notice = new StatusPacketNotice();
        $notice->mapFromXml(self::fixture('status_packet_notice.xml'));

        $this->assertSame('n1', $notice->getId());
        $this->assertSame('044525888', $notice->getSender()->getBank()->getBic());
        $this->assertSame('c1', $notice->getRecipient()->getCustomer()->getId());
        $this->assertSame('p1', $notice->getIDResultSuccessResponse());
        $this->assertSame('x1', $notice->getExtIDPacket());
        $this->assertSame(PacketStatus::ACCEPTED, $notice->getResult()->getStatus()->getCode());
        $this->assertNull($notice->getResult()->getError());
    }

    public function testStatusDocNotice()
    {
        $notice = new StatusDocNotice();
        $notice->mapFromXml(self::fixture('1c/StatusDocNotice.xml'));

        $this->assertSame('204e36e4-6910-416d-99b2-e8109e518744', $notice->getId());
        $this->assertSame('2019-04-22T09:34:46+03:00', $notice->getCreationDate());
        $this->assertSame('044525888', $notice->getSender()->getBank()->getBic());
        $this->assertSame('82007cb2-dfd1-4193-b903-16cc9b7231c9', $notice->getRecipient()->getCustomer()->getId());
        $this->assertSame('eafa28b5-6600-424c-b0d3-3785274d570d', $notice->getExtID());
        $this->assertNull($notice->getExtIDStatusRequest());

        $status = $notice->getResult()->getStatus();
        $this->assertSame(DocStatus::ACCEPTED, $status->getCode());
        $this->assertSame('Подписан', $status->getName());
    }

    public function testStatusDocNoticeWithError()
    {
        $xml = str_replace(
            '<Status>
            <Code>01</Code>
            <Name>Подписан</Name>
        </Status>',
            '<Error><Code>2205</Code><Description>Ошибка в реквизитах</Description><MoreInfo>Неверный БИК</MoreInfo></Error>',
            self::fixture('1c/StatusDocNotice.xml')
        );
        $xml = str_replace('</Result>', '</Result><ExtIDStatusRequest>0fa540c3-1aaa-4e06-8883-2c936fef05a5</ExtIDStatusRequest>', $xml);

        $this->assertValid($xml, 'StatusDocNotice');

        $notice = new StatusDocNotice();
        $notice->mapFromXml($xml);

        $this->assertNull($notice->getResult()->getStatus());
        $this->assertSame('2205', $notice->getResult()->getError()->getCode());
        $this->assertSame('Неверный БИК', $notice->getResult()->getError()->getMoreInfo());
        $this->assertSame('0fa540c3-1aaa-4e06-8883-2c936fef05a5', $notice->getExtIDStatusRequest());
    }

    public function testStatusRequest()
    {
        $request = (new StatusRequest())
            ->setId('0fa540c3-1aaa-4e06-8883-2c936fef05a5')
            ->setCreationDate('2016-04-22T10:09:11')
            ->setUserAgent('My App')
            ->setSender(self::customer())
            ->setRecipient(self::bank())
            ->setExtID('05688096-0806-4ef0-af4c-572f75dbaf7c');

        $this->assertValid((string) $request, 'StatusRequest');

        $official = (new XmlModelMapper())->map(self::fixture('1c/StatusRequest.xml'), new StatusRequest());

        $this->assertSame('05688096-0806-4ef0-af4c-572f75dbaf7c', $official->getExtID());
        $this->assertSame('7705260699', $official->getSender()->getInn());
        $this->assertSame('044525888', $official->getRecipient()->getBic());
        $this->assertValid($official->toXml(), 'StatusRequest');
    }

    public function testCancelationRequest()
    {
        $request = (new CancelationRequest())
            ->setId('e8caa148-82a3-446d-a10f-ff82ad514b7e')
            ->setCreationDate('2016-04-22T10:11:25')
            ->setSender(self::customer())
            ->setRecipient(self::bank())
            ->setExtID('05688096-0806-4ef0-af4c-572f75dbaf7c');

        $this->assertValid((string) $request, 'CancelationRequest');
        $this->assertNull($request->getReason());

        $request->setReason('Ошибка в реквизитах')->setDigest(new DigestType(base64_encode('digest'), '1.0'));
        $this->assertValid((string) $request, 'CancelationRequest');

        $official = (new XmlModelMapper())->map(self::fixture('1c/CancelationRequest.xml'), new CancelationRequest());

        $this->assertSame('05688096-0806-4ef0-af4c-572f75dbaf7c', $official->getExtID());
        $this->assertSame('Описание причины отзыва', $official->getReason());
        $this->assertNull($official->getDigest());
        $this->assertValid($official->toXml(), 'CancelationRequest');
    }

    public function testProbe()
    {
        $probe = (new Probe())
            ->setId('eafa28b5-6600-424c-b0d3-3785274d570d')
            ->setCreationDate('2016-04-22T09:33:57')
            ->setSender(self::customer())
            ->setRecipient(self::bank());

        $this->assertValid((string) $probe, 'Probe');
        $this->assertNull($probe->getUserAgent());

        $official = (new XmlModelMapper())->map(self::fixture('1c/Probe.xml'), new Probe());

        $this->assertSame('eafa28b5-6600-424c-b0d3-3785274d570d', $official->getId());
        $this->assertSame('82007cb2-dfd1-4193-b903-16cc9b7231c9', $official->getSender()->getId());
        $this->assertValid($official->toXml(), 'Probe');
    }

    public function testProbeWithDigest()
    {
        $probe = (new Probe())
            ->setId('eafa28b5-6600-424c-b0d3-3785274d570d')
            ->setCreationDate('2016-04-22T09:33:57')
            ->setSender(self::customer())
            ->setRecipient(self::bank())
            ->setDigest(new DigestType(base64_encode('digest'), '1.0'));

        $this->assertValid((string) $probe, 'Probe');

        $mapped = (new XmlModelMapper())->map((string) $probe, new Probe());

        $this->assertSame('digest', base64_decode($mapped->getDigest()->getData()));
        $this->assertSame('1.0', $mapped->getDigest()->getAlgorithmVersion());
    }

    public function testStatementRequestWithDigest()
    {
        $request = (new XmlModelMapper())->map(self::fixture('1c/StatementRequest.xml'), new StatementRequest());
        $this->assertNull($request->getDigest());

        $request->setDigest(new DigestType(base64_encode('digest'), '2.2.2'));

        $this->assertValid($request->toXml(), 'StatementRequest');
        $this->assertSame('2.2.2', (new XmlModelMapper())->map($request->toXml(), new StatementRequest())->getDigest()->getAlgorithmVersion());
    }

    public function testSettingsLogin()
    {
        $settings = new Settings();
        $settings->mapFromXml(self::fixture('1c/Settings.xml'));

        $this->assertSame('EFD857B5-7FA8-4195-8666-2CCADBC3C8DE', $settings->getId());
        $this->assertSame('DemoBankService', $settings->getUserAgent());
        $this->assertSame('044525888', $settings->getSender()->getBic());
        $this->assertSame('82007cb2-dfd1-4193-b903-16cc9b7231c9', $settings->getRecipient()->getId());

        $data = $settings->getData();
        $this->assertSame('82007cb2-dfd1-4193-b903-16cc9b7231c9', $data->getCustomerID());
        $this->assertSame('https://dbogate.demobank.ru/', $data->getBankServerAddress());
        $this->assertSame('2.3.1', $data->getFormatVersion());
        $this->assertSame('UTF-8', $data->getEncoding());
        $this->assertFalse($data->isCompress());
        $this->assertSame('user_login', $data->getLogon()->getLogin()->getUser());
        $this->assertNull($data->getLogon()->getCertificate());
        $this->assertNull($data->getCryptoParameters());
        $this->assertNull($data->getReceiptStatement());
        $this->assertNull($data->getLetters());
        $this->assertSame(['03', '05', '10', '11', '14', '30'], $data->getDocKinds());
        $this->assertNull($data->getDocuments()[0]->getSigned());
    }

    public function testSettingsCertificate()
    {
        $settings = new Settings();
        $settings->mapFromXml(self::fixture('1c/Settings_Logon_Certificate.xml'));

        $data = $settings->getData();
        $this->assertNull($data->getLogon()->getLogin());
        $this->assertSame('GOST28147', $data->getLogon()->getCertificate()->getEncryptingAlgorithm());

        $crypto = $data->getCryptoParameters();
        $this->assertSame('Crypto-Pro GOST R 34.10-2001 Cryptographic Service Provider', $crypto->getCSPName());
        $this->assertSame(75, $crypto->getCSPType());
        $this->assertSame('GOST R 34.10-2001', $crypto->getSignAlgorithm());
        $this->assertSame('GOST R 34.11-94', $crypto->getHashAlgorithm());
        $this->assertNull($crypto->getEncrypted());
        $this->assertNull($crypto->getBankCertificate());

        $groups = $crypto->getCustomerSignature()->getGroupSignatures();
        $this->assertCount(1, $groups);
        $this->assertSame(1, $groups[0]->getNumberGroup());
        $this->assertCount(1, $groups[0]->getCertificates());
        $this->assertStringStartsWith('MIIDhTCCAzKgAwIBAgIL', trim($groups[0]->getCertificates()[0]));

        $this->assertSame(['03', '04', '05', '10', '11', '14'], $data->getDocKinds());
        $this->assertNull($data->getDocuments()[0]->getSigned());
        $this->assertSame('(0)', $data->getDocuments()[1]->getSigned()->getRuleSignatures());
    }

    /**
     * Все необязательные элементы настроек и несколько групп подписей
     */
    public function testSettingsFull()
    {
        $xml = str_replace(
            [
                '<HashAlgorithm>GOST R 34.11-94</HashAlgorithm>',
                '</CustomerSignature>',
                '<Document docKind="03"/>',
                '</Data>',
            ],
            [
                '<HashAlgorithm>GOST R 34.11-94</HashAlgorithm><Encrypted><EncryptAlgorithm>GOST 28147-89</EncryptAlgorithm></Encrypted>'
                    . '<BankTrustedRootCertificate>Uk9PVA==</BankTrustedRootCertificate><BankCertificate>QkFOSw==</BankCertificate>',
                '<GroupSignatures numberGroup="2"><Certificate>QQ==</Certificate><Certificate>Qg==</Certificate></GroupSignatures></CustomerSignature>'
                    . '<URLAddinInfo>https://bank.example.ru/addin.zip</URLAddinInfo>',
                '<Document docKind="02"/><Document docKind="03"/>',
                '<ReceiptStatement><Login>statement</Login><Instructions>Позвоните в банк</Instructions></ReceiptStatement>'
                    . '<Letters><AttachmentsLimit>10485760</AttachmentsLimit>'
                    . '<LetterType><Code>01</Code><Name>Письмо свободного формата</Name></LetterType>'
                    . '<LetterType><Code>02</Code><Name>Запрос документов</Name></LetterType></Letters></Data>',
            ],
            self::fixture('1c/Settings_Logon_Certificate.xml')
        );

        $this->assertValid($xml, 'Settings');

        $settings = new Settings();
        $settings->mapFromXml($xml);

        $data = $settings->getData();
        $crypto = $data->getCryptoParameters();
        $this->assertSame('GOST 28147-89', $crypto->getEncrypted()->getEncryptAlgorithm());
        $this->assertSame('Uk9PVA==', $crypto->getBankTrustedRootCertificate());
        $this->assertSame('QkFOSw==', $crypto->getBankCertificate());
        $this->assertSame('https://bank.example.ru/addin.zip', $crypto->getURLAddinInfo());

        $groups = $crypto->getCustomerSignature()->getGroupSignatures();
        $this->assertCount(2, $groups);
        $this->assertSame(2, $groups[1]->getNumberGroup());
        $this->assertSame(['QQ==', 'Qg=='], $groups[1]->getCertificates());

        $this->assertSame('02', $data->getDocKinds()[0]);
        $this->assertSame('statement', $data->getReceiptStatement()->getLogin());
        $this->assertSame('Позвоните в банк', $data->getReceiptStatement()->getInstructions());

        $letters = $data->getLetters();
        $this->assertSame(10485760, $letters->getAttachmentsLimit());
        $this->assertCount(2, $letters->getLetterTypes());
        $this->assertSame('01', $letters->getLetterTypes()[0]->getCode());
        $this->assertSame('Запрос документов', $letters->getLetterTypes()[1]->getName());
    }
}
