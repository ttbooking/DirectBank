<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use Mapper\XmlModelMapper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TTBooking\DirectBank\Dictionary\DocKind;

/**
 * Исходящие платёжные документы: платёжное поручение и платёжное требование
 */
final class PaymentDocumentsTest extends TestCase
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

    protected static function customer(string $name, string $inn, string $account, string $bic): CustomerDetailsType
    {
        return (new CustomerDetailsType())
            ->setName($name)
            ->setINN($inn)
            ->setKPP('770501001')
            ->setAccount($account)
            ->setBank((new BankType())->setBic($bic)->setName('ДЕМО-БАНК')->setCity('Г. МОСКВА')->setCorrespAcc('30101810500000000219'));
    }

    protected static function payDocRu(): PayDocRu
    {
        return (new PayDocRu())
            ->setId((string) Uuid::uuid4())
            ->setCreationDate('2026-09-22T12:00:00')
            ->setUserAgent('My App')
            ->setSender((new CustomerPartyType())->setId('2806')->setName('Торговый дом Комплексный')->setInn('7705260699')->setKpp('770501001'))
            ->setRecipient((new BankPartyType())->setBic('044525888')->setName('ДЕМО-БАНК'))
            ->setData(
                (new PayDocRuApp())
                    ->setDocNo('14')
                    ->setDocDate('2026-09-22')
                    ->setSum(1234.56)
                    ->setPayer(self::customer('Торговый дом "Комплексный"', '7705260699', '40702810813123123222', '044525888'))
                    ->setPayee(self::customer('УФК по г. Москве', '7707329152', '03100643000000017300', '004525988'))
                    ->setTransitionKind('01')
                    ->setPriority('5')
                    ->setCode('0')
                    ->setPurpose('Налог на прибыль за август 2026')
                    ->setBudgetPaymentInfo(
                        (new BudgetPaymentInfoType())
                            ->setDrawerStatus('01')
                            ->setCBC('18210101011011000110')
                            ->setOKTMO('45380000')
                            ->setReason('ТП')
                            ->setTaxPeriod('МС.08.2026')
                            ->setDocNo('0')
                            ->setDocDate('0')
                    )
            );
    }

    public function testPayDocRu()
    {
        $payDocRu = self::payDocRu();

        $this->assertValid((string) $payDocRu, 'PayDocRu');

        $mapped = (new XmlModelMapper())->map((string) $payDocRu, new PayDocRu());
        $data = $mapped->getData();

        $this->assertSame('14', $data->getDocNo());
        $this->assertSame(1234.56, $data->getSum());
        $this->assertSame('7705260699', $data->getPayer()->getINN());
        $this->assertSame('03100643000000017300', $data->getPayee()->getAccount());
        $this->assertSame('004525988', $data->getPayee()->getBank()->getBic());
        $this->assertSame('01', $data->getTransitionKind());
        $this->assertSame('18210101011011000110', $data->getBudgetPaymentInfo()->getCBC());
        $this->assertSame('МС.08.2026', $data->getBudgetPaymentInfo()->getTaxPeriod());
        $this->assertNull($data->getBudgetPaymentInfo()->getPayType());
        $this->assertNull($mapped->getDigest());
    }

    public function testPayDocRuWithDigest()
    {
        $payDocRu = self::payDocRu()->setDigest(new DigestType(base64_encode('digest'), '1.0'));

        $this->assertValid((string) $payDocRu, 'PayDocRu');
        $this->assertSame('1.0', (new XmlModelMapper())->map((string) $payDocRu, new PayDocRu())->getDigest()->getAlgorithmVersion());
    }

    public function testPayDocRuInPacket()
    {
        $payDocRu = self::payDocRu();

        $packet = (new Packet())
            ->setId((string) Uuid::uuid4())
            ->setCreationDate('2026-09-22T12:00:00')
            ->setSender((new ParticipantType())->setCustomer($payDocRu->getSender()))
            ->setRecipient((new ParticipantType())->setBank($payDocRu->getRecipient()))
            ->setDocument(
                (new DocumentType())
                    ->setId($payDocRu->getId())
                    ->setDockind(DocKind::PAY_DOC_RU)
                    ->setData(base64_encode((string) $payDocRu))
            );

        $this->assertValid((string) $packet, 'Packet');
        $this->assertValid(base64_decode($packet->getDocument()->getData()), 'PayDocRu');
    }

    public function officialProvider(): array
    {
        return [
            'PayDocRu' => ['PayDocRu', PayDocRu::class],
            'PayRequest' => ['PayRequest', PayRequest::class],
        ];
    }

    /**
     * Официальные примеры 1С разбираются и собираются обратно в XML, проходящий XSD
     *
     * @dataProvider officialProvider
     */
    public function testOfficialExample(string $name, string $class)
    {
        $document = (new XmlModelMapper())->map(self::fixture("1c/$name.xml"), new $class());

        $this->assertSame('7705260699', $document->getSender()->getInn());
        $this->assertSame('044525888', $document->getRecipient()->getBic());
        $this->assertSame(15.0, $document->getData()->getSum());

        $this->assertValid($document->toXml(), $name);
        $this->assertEquals($document, (new XmlModelMapper())->map($document->toXml(), new $class()));
    }

    public function testOfficialPayRequest()
    {
        $data = (new XmlModelMapper())->map(self::fixture('1c/PayRequest.xml'), new PayRequest())->getData();

        $this->assertSame('15', $data->getDocNo());
        $this->assertSame('7704596181', $data->getPayer()->getINN());
        $this->assertSame('1', $data->getPaymentCondition());
        $this->assertNull($data->getAcceptTerm());
    }

    public function testPayRequest()
    {
        $payRequest = (new PayRequest())
            ->setId((string) Uuid::uuid4())
            ->setCreationDate('2026-09-22T12:00:00')
            ->setSender((new CustomerPartyType())->setId('2806'))
            ->setRecipient((new BankPartyType())->setBic('044525888'))
            ->setData(
                (new PayRequestApp())
                    ->setDocNo('15')
                    ->setDocDate('2026-09-22')
                    ->setSum(15)
                    ->setPayer(self::customer('ООО "Канцтовары"', '7704596181', '40702810401200000035', '044525999'))
                    ->setPayee(self::customer('Торговый дом "Комплексный"', '7705260699', '40702810813123123222', '044525888'))
                    ->setPurpose('за товар')
                    ->setPaymentCondition('2')
                    ->setAcceptTerm('5')
                    ->setDocDispatchDate('2026-09-20')
            );

        $this->assertValid((string) $payRequest, 'PayRequest');

        $data = (new XmlModelMapper())->map((string) $payRequest, new PayRequest())->getData();
        $this->assertSame('2', $data->getPaymentCondition());
        $this->assertSame('5', $data->getAcceptTerm());
        $this->assertSame('2026-09-20', $data->getDocDispatchDate());
    }
}
