<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use TTBooking\DirectBank\Dictionary\DefaultValue;
use TTBooking\DirectBank\Mapper\ConvertibleTrait;

/**
 * Платёжное требование (1С --> Банк, вид ЭД 11)
 *
 * @see \TTBooking\DirectBank\Dictionary\DocKind::PAY_REQUEST
 *
 * @xmlRoot PayRequest
 * @xmlEncoding utf-8
 */
class PayRequest implements \Stringable
{
    use ConvertibleTrait, DefaultFormatVersion;

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
    protected PayRequestApp $data;

    /**
     * @name Digest
     */
    protected ?DigestType $digest = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): PayRequest
    {
        $this->id = $id;
        return $this;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function setFormatVersion(string $formatVersion): PayRequest
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): PayRequest
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): PayRequest
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): CustomerPartyType
    {
        return $this->sender;
    }

    public function setSender(CustomerPartyType $sender): PayRequest
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): BankPartyType
    {
        return $this->recipient;
    }

    public function setRecipient(BankPartyType $recipient): PayRequest
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getData(): PayRequestApp
    {
        return $this->data;
    }

    public function setData(PayRequestApp $data): PayRequest
    {
        $this->data = $data;
        return $this;
    }

    public function getDigest(): ?DigestType
    {
        return $this->digest;
    }

    public function setDigest(?DigestType $digest): PayRequest
    {
        $this->digest = $digest;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}
