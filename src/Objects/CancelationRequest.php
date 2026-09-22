<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use TTBooking\DirectBank\Mapper\ConvertibleTrait;
use TTBooking\DirectBank\Dictionary\DefaultValue;

/**
 * Запрос об отзыве электронного документа (1С --> Банк, вид ЭД 04)
 *
 * @xmlRoot CancelationRequest
 * @xmlEncoding utf-8
 */
class CancelationRequest implements \Stringable
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
     * Идентификатор отзываемого электронного документа
     *
     * @name ExtID
     */
    protected string $extID;

    /**
     * Причина отзыва
     *
     * @name Reason
     */
    protected ?string $reason = null;

    /**
     * @name Digest
     */
    protected ?DigestType $digest = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): CancelationRequest
    {
        $this->id = $id;
        return $this;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function setFormatVersion(string $formatVersion): CancelationRequest
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): CancelationRequest
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): CancelationRequest
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): CustomerPartyType
    {
        return $this->sender;
    }

    public function setSender(CustomerPartyType $sender): CancelationRequest
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): BankPartyType
    {
        return $this->recipient;
    }

    public function setRecipient(BankPartyType $recipient): CancelationRequest
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getExtID(): string
    {
        return $this->extID;
    }

    public function setExtID(string $extID): CancelationRequest
    {
        $this->extID = $extID;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): CancelationRequest
    {
        $this->reason = $reason;
        return $this;
    }

    public function getDigest(): ?DigestType
    {
        return $this->digest;
    }

    public function setDigest(?DigestType $digest): CancelationRequest
    {
        $this->digest = $digest;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}
