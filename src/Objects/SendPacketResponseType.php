<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class SendPacketResponseType
{
    protected string $ID;

    public function getID(): string
    {
        return $this->ID;
    }
}