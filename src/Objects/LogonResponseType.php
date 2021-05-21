<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class LogonResponseType
{
    protected string $SID;

    /**
     * @return string
     */
    public function getSID(): string
    {
        return $this->SID;
    }


}