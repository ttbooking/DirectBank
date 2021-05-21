<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use Traits\ConvertibleTrait;
use Traits\MappableTrait;
use TTBooking\DirectBank\Dictionary\DefaultValue;

/**
 * @xmlRoot Packet
 * @xmlEncoding utf-8
 */
class Packet implements \Stringable
{
    use MappableTrait, ConvertibleTrait;

    /**
     * @xmlAttribute
     */
    protected ?string $xmlns='http://directbank.1c.ru/XMLSchema';

    /**
     * @xmlAttribute
     * @var string
     */
    protected string $id;
    /**
     * @xmlAttribute
     * @var string
     */
    protected string $formatVersion = DefaultValue::FORMAT_VERSION;
    /**
     * @xmlAttribute
     * @var string
     */
    protected string $creationDate;

    /**
     * @xmlAttribute
     * @var string
     */
    protected ?string $userAgent = null;

    /**
     * @name Sender
     */
    protected ParticipantType $sender;

    /**
     * @name Recipient
     */
    protected ParticipantType $recipient;

    /**
     * @name Document
     */
    protected DocumentType $document;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string  $id
     * @return Packet
     */
    public function setId(string $id): Packet
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
     * @return Packet
     */
    public function setFormatVersion(string $formatVersion): Packet
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
     * @return Packet
     */
    public function setCreationDate(string $creationDate): Packet
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
     * @return Packet
     */
    public function setUserAgent(string $userAgent): Packet
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @return \TTBooking\DirectBank\Objects\ParticipantType
     */
    public function getSender(): ParticipantType
    {
        return $this->sender;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\ParticipantType  $sender
     * @return Packet
     */
    public function setSender(ParticipantType $sender): Packet
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return \TTBooking\DirectBank\Objects\ParticipantType
     */
    public function getRecipient(): ParticipantType
    {
        return $this->recipient;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\ParticipantType  $recipient
     * @return Packet
     */
    public function setRecipient(ParticipantType $recipient): Packet
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return \TTBooking\DirectBank\Objects\DocumentType
     */
    public function getDocument(): DocumentType
    {
        return $this->document;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\DocumentType  $document
     * @return Packet
     */
    public function setDocument(DocumentType $document): Packet
    {
        $this->document = $document;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}