<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Электронная подпись документа (Document/Signature)
 */
class SignatureType
{
    /**
     * @xmlAttribute
     */
    protected string $x509IssuerName;

    /**
     * @xmlAttribute
     */
    protected string $x509SerialNumber;

    protected string $SignedData;

    public function getX509IssuerName(): string
    {
        return $this->x509IssuerName;
    }

    public function setX509IssuerName(string $x509IssuerName): SignatureType
    {
        $this->x509IssuerName = $x509IssuerName;
        return $this;
    }

    public function getX509SerialNumber(): string
    {
        return $this->x509SerialNumber;
    }

    public function setX509SerialNumber(string $x509SerialNumber): SignatureType
    {
        $this->x509SerialNumber = $x509SerialNumber;
        return $this;
    }

    /**
     * Подпись в base64
     */
    public function getSignedData(): string
    {
        return $this->SignedData;
    }

    public function setSignedData(string $signedData): SignatureType
    {
        $this->SignedData = $signedData;
        return $this;
    }
}
