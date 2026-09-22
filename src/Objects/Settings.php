<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use Traits\MappableTrait;

/**
 * Настройки обмена с банком (1С <-- Банк, вид ЭД 06)
 *
 * @xmlRoot Settings
 * @xmlEncoding utf-8
 */
class Settings
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

    protected BankPartyType $Sender;

    protected CustomerPartyType $Recipient;

    protected SettingsDataType $Data;

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

    public function getSender(): BankPartyType
    {
        return $this->Sender;
    }

    public function getRecipient(): CustomerPartyType
    {
        return $this->Recipient;
    }

    public function getData(): SettingsDataType
    {
        return $this->Data;
    }
}
