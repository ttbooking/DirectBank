<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class Stamp extends BankType
{
    protected ?string $Branch = null;

    protected ?StatusType $Status= null;

    public function getBranch(): ?string
    {
        return $this->Branch;
    }

    public function getStatus(): ?StatusType
    {
        return $this->Status;
    }
}