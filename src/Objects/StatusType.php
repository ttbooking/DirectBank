<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class StatusType
{
    protected string $Code;

    protected ?string $Name = null;

    protected ?string $MoreInfo = null;
}