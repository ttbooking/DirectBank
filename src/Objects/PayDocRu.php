<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use TTBooking\DirectBank\Dictionary\DefaultValue;
use TTBooking\DirectBank\Mapper\ConvertibleTrait;

/**
 * Платёжное поручение (1С --> Банк, вид ЭД 10)
 *
 * @see \TTBooking\DirectBank\Dictionary\DocKind::PAY_DOC_RU
 *
 * @xmlRoot PayDocRu
 * @xmlEncoding utf-8
 */
class PayDocRu implements \Stringable
{
    use ConvertibleTrait;

    /**
     * @xmlAttribute
     */
    protected string $xmlns = 'http://directbank.1c.ru/XMLSchema';

    /**
     * @xmlAttribute
     */
    protected string $id;

    /**
     * @xmlAttribute
     */
    protected string $formatVersion = DefaultValue::FORMAT_VERSION;

    /**
     * @xmlAttribute
     */
    protected string $creationDate;

    /**
     * @xmlAttribute
     */
    protected ?string $userAgent = null;

    /**
     * @name Sender
     */
    protected CustomerPartyType $sender;

    /**
     * @name Recipient
     */
    protected BankPartyType $recipient;

    /**
     * @name Data
     */
    protected PayDocRuApp $data;

    /**
     * @name Digest
     */
    protected ?DigestType $digest = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): PayDocRu
    {
        $this->id = $id;
        return $this;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function setFormatVersion(string $formatVersion): PayDocRu
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): PayDocRu
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): PayDocRu
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): CustomerPartyType
    {
        return $this->sender;
    }

    public function setSender(CustomerPartyType $sender): PayDocRu
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): BankPartyType
    {
        return $this->recipient;
    }

    public function setRecipient(BankPartyType $recipient): PayDocRu
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getData(): PayDocRuApp
    {
        return $this->data;
    }

    public function setData(PayDocRuApp $data): PayDocRu
    {
        $this->data = $data;
        return $this;
    }

    public function getDigest(): ?DigestType
    {
        return $this->digest;
    }

    public function setDigest(?DigestType $digest): PayDocRu
    {
        $this->digest = $digest;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}
