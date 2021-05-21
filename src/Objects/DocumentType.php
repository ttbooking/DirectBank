<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

use TTBooking\DirectBank\Dictionary\DefaultValue;

class DocumentType
{
    /**
     * @xmlAttribute
     */
    protected string $id;

    /**
     * @xmlAttribute
     */
    protected string $dockind;

    /**
     * @xmlAttribute
     */
    protected string $formatVersion = DefaultValue::FORMAT_VERSION;

    /**
     * @xmlAttribute
     */
    protected ?bool $testOnly = null;

    /**
     * @xmlAttribute
     */
    protected ?bool $compressed = null;

    /**
     * @xmlAttribute
     */
    protected ?bool $encrypted = null;

    /**
     * @xmlAttribute
     */
    protected ?bool $signResponse = null;

    /**
     * @xmlAttribute
     */
    protected ?bool $notifyRequired = null;

    /**
     * @xmlAttribute
     */
    protected ?string $extID = null;

    /**
     * @name Data
     */
    protected string $data;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string  $id
     * @return DocumentType
     */
    public function setId(string $id): DocumentType
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDockind(): string
    {
        return $this->dockind;
    }

    /**
     * @param  string  $dockind
     * @return DocumentType
     */
    public function setDockind(string $dockind): DocumentType
    {
        $this->dockind = $dockind;
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
     * @return DocumentType
     */
    public function setFormatVersion(string $formatVersion): DocumentType
    {
        $this->formatVersion = $formatVersion;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTestOnly(): bool
    {
        return $this->testOnly;
    }

    /**
     * @param  bool  $testOnly
     * @return DocumentType
     */
    public function setTestOnly(bool $testOnly): DocumentType
    {
        $this->testOnly = $testOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCompressed(): bool
    {
        return $this->compressed;
    }

    /**
     * @param  bool  $compressed
     * @return DocumentType
     */
    public function setCompressed(bool $compressed): DocumentType
    {
        $this->compressed = $compressed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }

    /**
     * @param  bool  $encrypted
     * @return DocumentType
     */
    public function setEncrypted(bool $encrypted): DocumentType
    {
        $this->encrypted = $encrypted;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSignResponse(): bool
    {
        return $this->signResponse;
    }

    /**
     * @param  bool  $signResponse
     * @return DocumentType
     */
    public function setSignResponse(bool $signResponse): DocumentType
    {
        $this->signResponse = $signResponse;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtID(): ?string
    {
        return $this->extID;
    }

    /**
     * @param  string|null  $extID
     * @return DocumentType
     */
    public function setExtID(?string $extID): DocumentType
    {
        $this->extID = $extID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotifyRequired()
    {
        return $this->notifyRequired;
    }

    /**
     * @param  mixed  $notifyRequired
     * @return DocumentType
     */
    public function setNotifyRequired($notifyRequired)
    {
        $this->notifyRequired = $notifyRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param  string  $data
     * @return DocumentType
     */
    public function setData(string $data): DocumentType
    {
        $this->data = $data;
        return $this;
    }
}