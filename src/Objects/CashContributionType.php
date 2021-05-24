<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class CashContributionType extends OtherPaymentDataType
{
    protected ?PersonType $Person = null;
    protected ?string $Symbol = null;
    protected ?string $Source = null;

    public function getPerson(): ?PersonType
    {
        return $this->Person;
    }

    public function getSymbol(): ?string
    {
        return $this->Symbol;
    }

    public function getSource(): ?string
    {
        return $this->Source;
    }
}