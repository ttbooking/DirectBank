<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use PHPUnit\Framework\TestCase;

class StatementTest extends TestCase
{
    protected string $xml;
    protected string $xml_empty_order_info;

    protected function setUp(): void
    {
        $this->xml = file_get_contents(__DIR__ . '/../Fixture/xml/statement.xml');
        $this->xml_empty_order_info = file_get_contents(__DIR__ . '/../Fixture/xml/statement_empty_order_info.xml');
    }

    public function testFromXml()
    {
        $statement = new Statement();

        $statement->mapFromXml($this->xml);

        $domXml = new \DOMDocument();
        $domXml->loadXML($this->xml);

        $xPath = new \DOMXPath($domXml);
        $xPath->registerNamespace('ns', 'http://directbank.1c.ru/XMLSchema');

        $payDocs1 = [];
        /** @var \DOMElement $item */
        foreach ($xPath->query('//ns:Statement/ns:Data/ns:OperationInfo/ns:PayDoc')->getIterator() as $item) {
            $payDocs1[] = [
                'id' => $item->getAttribute('id'),
                'docKind' => $item->getAttribute('docKind')
            ];
        }

        $payDocs2 = [];
        foreach ($statement->getData()->getOperationInfo() as $operationInfo) {
            $payDocs2[] = [
                'id' => $operationInfo->getPayDoc()->getId(),
                'docKind' => $operationInfo->getPayDoc()->getDocKind(),
            ];
        }

        $this->assertEquals($payDocs1, $payDocs2);

        $statement = new Statement();
        $statement->mapFromXml($this->xml_empty_order_info);

        $this->assertEquals([], $statement->getData()->getOperationInfo());
    }

    /**
     * Официальный пример выписки из описания стандарта 1С
     */
    public function testOfficialExample()
    {
        $statement = new Statement();
        $statement->mapFromXml(file_get_contents(__DIR__ . '/../Fixture/xml/1c/Statement.xml'));

        $this->assertSame('f7cbc6af-33dd-4c37-b67d-7400e1c327ad', $statement->getId());
        $this->assertSame('044525888', $statement->getSender()->getBic());
        $this->assertSame('7705260699', $statement->getRecipient()->getInn());
        $this->assertSame('39f9553d-67b1-4314-a2b1-8bddc99e0f42', $statement->getExtIDStatementRequest());

        $data = $statement->getData();
        $this->assertSame('40702810500000000001', $data->getAccount());
        $this->assertSame('2016-05-04T00:00:00.000', $data->getDateFrom());
        $this->assertSame(139280.91, $data->getOpeningBalance());
        $this->assertSame(88970.02, $data->getClosingBalance());
        $this->assertNull($data->getTotalDebits());
        $this->assertSame('044525888', $data->getStamp()->getBic());
        $this->assertNull($data->getStamp()->getBranch());

        $operations = $data->getOperationInfo();
        $this->assertCount(1, $operations);
        $this->assertSame(1, $operations[0]->getDC());
        $this->assertSame('2016-05-04', $operations[0]->getDate());

        $payDoc = $operations[0]->getPayDoc();
        $this->assertSame('768', $payDoc->getId());
        $this->assertSame('10', $payDoc->getDocKind());

        $payDocRu = $payDoc->getPayDocRu();
        $this->assertSame('768', $payDocRu->getDocNo());
        $this->assertSame(14.0, $payDocRu->getSum());
        $this->assertSame('7705260699', $payDocRu->getPayer()->getINN());
        $this->assertSame('40802810300020007955', $payDocRu->getPayee()->getAccount());
        $this->assertSame('046577413', $payDocRu->getPayee()->getBank()->getBic());
        $this->assertSame('5', $payDocRu->getPriority());
        $this->assertStringStartsWith('За транспортные услуги', $payDocRu->getPurpose());
        $this->assertNull($payDocRu->getBudgetPaymentInfo());

        $this->assertSame('DemoBankService', $statement->getUserAgent());

        $status = $operations[0]->getStamp()->getStatus();
        $this->assertSame('02', $status->getCode());
        $this->assertSame('Исполнен', $status->getName());
        $this->assertSame('Платежный документ исполнен банком', $status->getMoreInfo());
    }

    /**
     * Выписка только с обязательными по XSD элементами
     */
    public function testMinimal()
    {
        $xml = file_get_contents(__DIR__ . '/../Fixture/xml/statement_minimal.xml');

        $this->assertValid($xml);

        $statement = new Statement();
        $statement->mapFromXml($xml);

        $data = $statement->getData();
        $this->assertNull($data->getDateFrom());
        $this->assertNull($data->getOpeningBalance());
        $this->assertNull($data->getStamp());
        $this->assertSame(0.0, $data->getClosingBalance());

        $operation = $data->getOperationInfo()[0];
        $this->assertNull($operation->getStamp());
        $this->assertNull($operation->getExtID());
        $this->assertSame(7.0, $operation->getPayDoc()->getInnerDoc()->getSum());
    }

    /**
     * Выписка со всеми вариантами PayDoc и всеми необязательными элементами
     */
    public function testAllDocKinds()
    {
        $xml = file_get_contents(__DIR__ . '/../Fixture/xml/statement_all_doc_kinds.xml');

        $this->assertValid($xml);

        $statement = new Statement();
        $statement->mapFromXml($xml);

        $payDocs = [];
        foreach ($statement->getData()->getOperationInfo() as $operation) {
            $payDocs[$operation->getPayDoc()->getDocKind()] = $operation->getPayDoc();
        }

        $this->assertSame(['10', '11', '12', '17', '18', '16', '13', '24', '25'], array_map('strval', array_keys($payDocs)));

        $check = $payDocs['25']->getCheck();
        $this->assertSame(9.0, $check->getSum());
        $this->assertSame('0001', $check->getDataPrinting()->getCheckNumber());
        $this->assertCount(2, $check->getDetails());
        $this->assertSame('40', $check->getDetails()[0]->getSymbol());
        $this->assertSame('zp', $check->getDetails()[0]->getPurpose());
        $this->assertSame('53', $check->getDetails()[1]->getSymbol());
        $this->assertNull($check->getDetails()[1]->getPurpose());
        $this->assertSame(5.0, $check->getDetails()[1]->getSum());

        $this->assertSame('Bank', $statement->getUserAgent());
        $this->assertSame('Комиссия', $payDocs['13']->getInnerDoc()->getInnerDocKind());
        $this->assertSame(7.0, $payDocs['13']->getInnerDoc()->getSum());
        $this->assertSame('Br', $statement->getData()->getStamp()->getBranch());
    }

    public function testCheckWithSingleDetails()
    {
        $xml = str_replace(
            '<Details><Symbol>53</Symbol><Sum>5</Sum></Details>',
            '',
            file_get_contents(__DIR__ . '/../Fixture/xml/statement_all_doc_kinds.xml')
        );

        $statement = new Statement();
        $statement->mapFromXml($xml);

        foreach ($statement->getData()->getOperationInfo() as $operation) {
            if ($check = $operation->getPayDoc()->getCheck()) {
                $this->assertCount(1, $check->getDetails());
                $this->assertSame('40', $check->getDetails()[0]->getSymbol());
            }
        }
    }

    protected function assertValid(string $xml): void
    {
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_Statement.xsd'));
    }
}
