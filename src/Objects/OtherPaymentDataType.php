<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class OtherPaymentDataType
{
    protected ?string $DocNo = null;
    protected ?string $DocDate = null;

    protected float $Sum;
    protected ?OtherCustomerDetailsType $Payer = null;
    protected ?OtherCustomerDetailsType $Payee = null;
    protected ?string $TransitionKind = null;
    protected ?string $Code = null;
    protected ?string $Purpose = null;

    public function getDocNo(): ?string
    {
        return $this->DocNo;
    }

    public function getDocDate(): ?string
    {
        return $this->DocDate;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

    public function getPayer(): ?OtherCustomerDetailsType
    {
        return $this->Payer;
    }

    public function getPayee(): ?OtherCustomerDetailsType
    {
        return $this->Payee;
    }

    public function getTransitionKind(): ?string
    {
        return $this->TransitionKind;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function getPurpose(): ?string
    {
        return $this->Purpose;
    }

}