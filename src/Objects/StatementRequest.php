<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use Mapper\ModelMapper;
use Traits\ConvertibleTrait;
use TTBooking\DirectBank\Attributes\Name;
use TTBooking\DirectBank\Dictionary\DefaultValue;

/**
 * @xmlRoot StatementRequest
 * @xmlEncoding utf-8
 */
class StatementRequest implements \Stringable
{
    use ConvertibleTrait;

    /**
     * @xmlAttribute
     */
    protected string $xmlns='http://directbank.1c.ru/XMLSchema';

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
    protected string $userAgent;

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
    protected StatementRequestData $data;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string  $id
     * @return StatementRequest
     */
    public function setId(string $id): StatementRequest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    /**
     * @param  string  $formatVersion
     * @return StatementRequest
     */
    public function setFormatVersion(string $formatVersion): StatementRequest
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param  string  $creationDate
     * @return StatementRequest
     */
    public function setCreationDate(string $creationDate): StatementRequest
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param  string  $userAgent
     * @return StatementRequest
     */
    public function setUserAgent(string $userAgent): StatementRequest
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): CustomerPartyType
    {
        return $this->sender;
    }

    public function setSender(CustomerPartyType $sender): StatementRequest
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): BankPartyType
    {
        return $this->recipient;
    }

    public function setRecipient(BankPartyType $recipient): StatementRequest
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return \TTBooking\DirectBank\Objects\StatementRequestData
     */
    public function getData(): StatementRequestData
    {
        return $this->data;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\StatementRequestData  $data
     * @return StatementRequest
     */
    public function setData(StatementRequestData $data): StatementRequest
    {
        $this->data = $data;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}