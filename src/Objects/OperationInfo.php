<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * @xmlRoot OperationInfo
 * @xmlEncoding utf-8
 */
class OperationInfo
{
    protected PayDoc $PayDoc;

    /**
     * признак дебета (1) / кредита (2)
     */
    protected int $DC;

    protected string $Date;

    protected ?string $ExtID = null;

    protected ?Stamp $Stamp = null;

    public function getPayDoc(): PayDoc
    {
        return $this->PayDoc;
    }

    public function getDC(): int
    {
        return $this->DC;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function getExtID(): ?string
    {
        return $this->ExtID;
    }

    public function getStamp(): ?Stamp
    {
        return $this->Stamp;
    }
}