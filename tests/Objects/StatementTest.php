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
}
