<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Вложение письма (Letter/Data/Attachment)
 */
class LetterAttachmentType
{
    protected BinaryFileType $BinaryFile;

    /**
     * @var SignatureType[]
     */
    protected array $Signature = [];

    public function __construct(BinaryFileType $file)
    {
        $this->BinaryFile = $file;
    }

    public function getBinaryFile(): BinaryFileType
    {
        return $this->BinaryFile;
    }

    /**
     * @return SignatureType[]
     */
    public function getSignatures(): array
    {
        return $this->Signature;
    }

    public function addSignature(SignatureType $signature): LetterAttachmentType
    {
        $this->Signature[] = $signature;
        return $this;
    }
}
