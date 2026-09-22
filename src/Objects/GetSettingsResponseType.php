<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Ответ на запрос настроек обмена (GetSettings)
 */
class GetSettingsResponseType
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

    protected GetSettingsDataType $Data;

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

    /**
     * Настройки обмена в base64
     */
    public function getData(): string
    {
        return $this->Data->getValue();
    }

    public function getDockind(): string
    {
        return $this->Data->getDockind();
    }

    /**
     * Разобранные настройки обмена
     */
    public function getSettings(): Settings
    {
        $settings = new Settings();
        $settings->mapFromXml(base64_decode($this->getData(), true) ?: throw new \UnexpectedValueException('Settings data is not valid base64.'));

        return $settings;
    }
}
