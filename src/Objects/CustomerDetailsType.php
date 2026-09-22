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

    public function setName(string $name): static
    {
        $this->Name = $name;
        return $this;
    }

    public function setINN(?string $inn): static
    {
        $this->INN = $inn;
        return $this;
    }

    public function setKPP(?string $kpp): static
    {
        $this->KPP = $kpp;
        return $this;
    }

    public function setAccount(?string $account): static
    {
        $this->Account = $account;
        return $this;
    }

    public function setBank(BankType $bank): static
    {
        $this->Bank = $bank;
        return $this;
    }
}
