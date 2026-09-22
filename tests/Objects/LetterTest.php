<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use TTBooking\DirectBank\Dictionary\DefaultValue;
use TTBooking\DirectBank\Dictionary\DocKind;

final class LetterTest extends TestCase
{
    protected function assertValid(string $xml, string $schema): void
    {
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue($dom->schemaValidate(__DIR__ . '/../Fixture/xsd/1C-Bank_' . $schema . '.xsd'));
    }

    protected static function letter(): Letter
    {
        $pdf = "%PDF-1.4\n" . random_bytes(64);

        return (new Letter())
            ->setId('6a2f1c0e-5b3d-4e8a-9c7f-1d2e3f4a5b6c')
            ->setCreationDate('2026-09-23T12:00:00')
            ->setUserAgent('My App')
            ->setSender((new ParticipantType())->setCustomer((new CustomerPartyType())->setId('2806')->setInn('7705260699')))
            ->setRecipient((new ParticipantType())->setBank((new BankPartyType())->setBic('044525888')))
            ->setData(
                (new LetterDataType())
                    ->setCorrespondenceID('0b9c8d7e-6f5a-4b3c-2d1e-0f9a8b7c6d5e')
                    ->setDocNum('7')
                    ->setDocDate('2026-09-23')
                    ->setLetterTypeCode('01')
                    ->setTheme('Уточнение назначения платежа')
                    ->setText("Прошу уточнить назначение платежа по поручению № 14.\nСпасибо.")
                    ->addAttachment(
                        (new LetterAttachmentType(BinaryFileType::fromContents($pdf, 'счёт 42.pdf', new \DateTimeImmutable('2026-09-22 10:00:00'))))
                            ->addSignature((new SignatureType())->setX509IssuerName('CN=Test CA')->setX509SerialNumber('0A1B')->setSignedData(base64_encode('signature')))
                    )
                    ->addAttachment(new LetterAttachmentType(BinaryFileType::fromContents(DefaultValue::BOM . "<?xml version=\"1.0\"?><a/>", 'act.xml')))
                    ->setLinkedDoc(new LinkedDocType('05688096-0806-4ef0-af4c-572f75dbaf7c', DocKind::PAY_DOC_RU))
            );
    }

    public function testLetter()
    {
        $letter = self::letter();

        $this->assertSame('2.3.2', $letter->getFormatVersion());
        $this->assertValid((string) $letter, 'Letter');

        $mapped = new Letter();
        $mapped->mapFromXml((string) $letter);

        $this->assertEquals($letter, $mapped);

        $data = $mapped->getData();
        $this->assertSame('0b9c8d7e-6f5a-4b3c-2d1e-0f9a8b7c6d5e', $data->getCorrespondenceID());
        $this->assertNull($data->getLinkedID());
        $this->assertSame('01', $data->getLetterTypeCode());
        $this->assertSame("Прошу уточнить назначение платежа по поручению № 14.\nСпасибо.", $data->getText());
        $this->assertSame(DocKind::PAY_DOC_RU, $data->getLinkedDoc()->getDockind());

        [$pdf, $xml] = $data->getAttachments();
        $file = $pdf->getBinaryFile();
        $this->assertSame('счёт 42.pdf', $file->getName());
        $this->assertSame('pdf', $file->getExtension());
        $this->assertSame(73, $file->getSize());
        $this->assertSame(crc32($file->getContents()), $file->getCrc());
        $this->assertTrue($file->isIntact());
        $this->assertSame('2026-09-22T10:00:00', $file->getCreationDate());
        $this->assertCount(1, $pdf->getSignatures());
        $this->assertSame('0A1B', $pdf->getSignatures()[0]->getX509SerialNumber());

        $this->assertSame(DefaultValue::BOM, substr($xml->getBinaryFile()->getContents(), 0, 3));
        $this->assertSame([], $xml->getSignatures());
    }

    public function testMinimalLetter()
    {
        $letter = (new Letter())
            ->setId((string) Uuid::uuid4())
            ->setCreationDate('2026-09-23T12:00:00')
            ->setSender((new ParticipantType())->setBank((new BankPartyType())->setBic('044525888')))
            ->setRecipient((new ParticipantType())->setCustomer((new CustomerPartyType())->setId('2806')))
            ->setData((new LetterDataType())->setDocNum('1')->setDocDate('2026-09-23')->setText('Уведомление'));

        $this->assertValid((string) $letter, 'Letter');

        $mapped = new Letter();
        $mapped->mapFromXml((string) $letter);

        $this->assertSame('044525888', $mapped->getSender()->getBank()->getBic());
        $this->assertSame([], $mapped->getData()->getAttachments());
        $this->assertNull($mapped->getData()->getTheme());
        $this->assertNull($mapped->getData()->getLinkedDoc());
    }

    public function testDamagedAttachment()
    {
        $letter = new Letter();
        $letter->mapFromXml(str_replace(' size="73"', ' size="74"', (string) self::letter()));

        $this->assertFalse($letter->getData()->getAttachments()[0]->getBinaryFile()->isIntact());
    }

    public function testAttachmentFromFile()
    {
        $path = tempnam(sys_get_temp_dir(), 'db');
        file_put_contents($path, 'contents');

        try {
            $file = BinaryFileType::fromFile($path, 'report.txt');
        } finally {
            unlink($path);
        }

        $this->assertSame('report.txt', $file->getName());
        $this->assertSame('txt', $file->getExtension());
        $this->assertSame(8, $file->getSize());
        $this->assertSame(crc32('contents'), $file->getCrc());
        $this->assertSame('contents', $file->getContents());

        $this->expectException(\InvalidArgumentException::class);
        BinaryFileType::fromFile($path);
    }

    public function testLetterInPacket()
    {
        $letter = self::letter();

        $packet = (new Packet())
            ->setId((string) Uuid::uuid4())
            ->setCreationDate('2026-09-23T12:00:00')
            ->setSender($letter->getSender())
            ->setRecipient($letter->getRecipient())
            ->setDocument(
                (new DocumentType())->setId($letter->getId())->setDockind(DocKind::LETTER)->setData(base64_encode((string) $letter))
            );

        $this->assertValid((string) $packet, 'Packet');
    }
}
