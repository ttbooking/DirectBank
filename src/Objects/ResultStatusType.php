<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class ResultStatusType
{
    protected ?ErrorType $Error = null;

    protected ?StatusType $Status = null;

    public function getError(): ?ErrorType
    {
        return $this->Error;
    }

    public function getStatus(): ?StatusType
    {
        return $this->Status;
    }
}