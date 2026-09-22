<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class StatusType
{
    protected string $Code;

    protected ?string $Name = null;

    protected ?string $MoreInfo = null;

    public function getCode(): string
    {
        return $this->Code;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getMoreInfo(): ?string
    {
        return $this->MoreInfo;
    }
}