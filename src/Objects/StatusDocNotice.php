<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use Traits\MappableTrait;

/**
 * Извещение о состоянии электронного документа (1С <-- Банк, вид ЭД 02)
 *
 * @xmlRoot StatusDocNotice
 * @xmlEncoding utf-8
 */
class StatusDocNotice
{
    use MappableTrait;

    /**
     * @xmlAttribute
     */
    protected string $id;

    /**
     * @xmlAttribute
     */
    protected string $formatVersion;

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

    //Идентификатор электронного документа, о состоянии которого сообщается
    protected string $ExtID;

    protected ResultStatusType $Result;

    //Идентификатор запроса о состоянии, в ответ на который сформировано извещение
    protected ?string $ExtIDStatusRequest = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getSender(): ParticipantType
    {
        return $this->Sender;
    }

    public function getRecipient(): ParticipantType
    {
        return $this->Recipient;
    }

    public function getExtID(): string
    {
        return $this->ExtID;
    }

    public function getResult(): ResultStatusType
    {
        return $this->Result;
    }

    public function getExtIDStatusRequest(): ?string
    {
        return $this->ExtIDStatusRequest;
    }
}
