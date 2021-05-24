<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class AuthorType extends BankType
{
    protected ?string $Branch = null;

    public function getBranch(): ?string
    {
        return $this->Branch;
    }
}