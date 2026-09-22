<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use Traits\ConvertibleTrait;
use TTBooking\DirectBank\Dictionary\DefaultValue;

/**
 * Запрос о состоянии электронного документа (1С --> Банк, вид ЭД 03)
 *
 * @xmlRoot StatusRequest
 * @xmlEncoding utf-8
 */
class StatusRequest implements \Stringable
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
     * Идентификатор электронного документа, состояние которого запрашивается
     *
     * @name ExtID
     */
    protected string $extID;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): StatusRequest
    {
        $this->id = $id;
        return $this;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function setFormatVersion(string $formatVersion): StatusRequest
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): StatusRequest
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): StatusRequest
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): CustomerPartyType
    {
        return $this->sender;
    }

    public function setSender(CustomerPartyType $sender): StatusRequest
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): BankPartyType
    {
        return $this->recipient;
    }

    public function setRecipient(BankPartyType $recipient): StatusRequest
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getExtID(): string
    {
        return $this->extID;
    }

    public function setExtID(string $extID): StatusRequest
    {
        $this->extID = $extID;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}
