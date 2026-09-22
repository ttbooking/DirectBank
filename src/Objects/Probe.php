<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use TTBooking\DirectBank\Mapper\ConvertibleTrait;
use TTBooking\DirectBank\Dictionary\DefaultValue;

/**
 * Запрос-зонд для проверки работоспособности сервиса (1С --> Банк, вид ЭД 05)
 *
 * @xmlRoot Probe
 * @xmlEncoding utf-8
 */
class Probe implements \Stringable
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
     * @name Digest
     */
    protected ?DigestType $digest = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Probe
    {
        $this->id = $id;
        return $this;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function setFormatVersion(string $formatVersion): Probe
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): Probe
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): Probe
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): CustomerPartyType
    {
        return $this->sender;
    }

    public function setSender(CustomerPartyType $sender): Probe
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): BankPartyType
    {
        return $this->recipient;
    }

    public function setRecipient(BankPartyType $recipient): Probe
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getDigest(): ?DigestType
    {
        return $this->digest;
    }

    public function setDigest(?DigestType $digest): Probe
    {
        $this->digest = $digest;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}
