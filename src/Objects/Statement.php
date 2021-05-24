<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use Traits\MappableTrait;

/**
 * @xmlEncoding utf-8
 * @xmlRoot Statement
 */
class Statement
{
    use MappableTrait;

    /**
     * @xmlAttribute
     */
    protected string $xmlns = "http://directbank.1c.ru/XMLSchema";

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

    protected BankPartyType $Sender;

    protected CustomerPartyType $Recipient;

    protected StatementData $Data;

    protected ?string $ExtIDStatementRequest = null;

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

    public function getSender(): BankPartyType
    {
        return $this->Sender;
    }

    public function getRecipient(): CustomerPartyType
    {
        return $this->Recipient;
    }

    public function getData(): StatementData
    {
        return $this->Data;
    }

    public function getExtIDStatementRequest(): ?string
    {
        return $this->ExtIDStatementRequest;
    }
}