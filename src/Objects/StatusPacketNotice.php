<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class StatusPacketNotice
{
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

    protected string $IDResultSuccessResponse;

    protected ResultStatusType $Result;

    protected ?string $ExtIDPacket = null;

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

    public function getIDResultSuccessResponse(): string
    {
        return $this->IDResultSuccessResponse;
    }

    public function getResult(): ResultStatusType
    {
        return $this->Result;
    }

    public function getExtIDPacket(): ?string
    {
        return $this->ExtIDPacket;
    }
}