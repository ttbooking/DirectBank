<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class PersonType
{
    protected ?string $FullName = null;
    protected ?string $IdentityDocument = null;

    public function getFullName(): ?string
    {
        return $this->FullName;
    }

    public function getIdentityDocument(): ?string
    {
        return $this->IdentityDocument;
    }

}