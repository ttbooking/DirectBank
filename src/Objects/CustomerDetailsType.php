<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class CustomerDetailsType
{
    protected string $Name;
    protected ?string $INN = null;
    protected ?string $KPP = null;
    protected ?string $Account = null;
    protected BankType $Bank;

    public function getName(): string
    {
        return $this->Name;
    }

    public function getINN(): ?string
    {
        return $this->INN;
    }

    public function getKPP(): ?string
    {
        return $this->KPP;
    }

    public function getAccount(): ?string
    {
        return $this->Account;
    }

    public function getBank(): BankType
    {
        return $this->Bank;
    }
}