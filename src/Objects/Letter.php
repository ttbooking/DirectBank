<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use Traits\MappableTrait;
use TTBooking\DirectBank\Dictionary\DefaultValue;
use TTBooking\DirectBank\Mapper\ConvertibleTrait;

/**
 * Письмо (1С <--> Банк, вид ЭД 40), с версии 2.3.1
 *
 * @see \TTBooking\DirectBank\Dictionary\DocKind::LETTER
 *
 * @xmlRoot Letter
 * @xmlEncoding utf-8
 */
class Letter implements \Stringable
{
    use MappableTrait, ConvertibleTrait, DefaultFormatVersion;

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

    protected ParticipantType $Sender;

    protected ParticipantType $Recipient;

    protected LetterDataType $Data;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Letter
    {
        $this->id = $id;
        return $this;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function setFormatVersion(string $formatVersion): Letter
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): Letter
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): Letter
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getSender(): ParticipantType
    {
        return $this->Sender;
    }

    public function setSender(ParticipantType $sender): Letter
    {
        $this->Sender = $sender;
        return $this;
    }

    public function getRecipient(): ParticipantType
    {
        return $this->Recipient;
    }

    public function setRecipient(ParticipantType $recipient): Letter
    {
        $this->Recipient = $recipient;
        return $this;
    }

    public function getData(): LetterDataType
    {
        return $this->Data;
    }

    public function setData(LetterDataType $data): Letter
    {
        $this->Data = $data;
        return $this;
    }

    public function __toString()
    {
        return $this->toXml();
    }
}
