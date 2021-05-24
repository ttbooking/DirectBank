<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class StatementData
{
    protected string $StatementType;

    protected ?string $DateFrom = null;

    protected string $DateTo;

    protected string $Account;

    protected BankType $Bank;

    protected ?float $OpeningBalance = null;

    protected ?float $TotalDebits = null;

    protected ?float $TotalCredits = null;

    protected float $ClosingBalance;

    /**
     * @var OperationInfo[]
     */
    protected array $OperationInfo;

    protected Stamp $Stamp;

    public function getStatementType(): string
    {
        return $this->StatementType;
    }

    public function getDateFrom(): ?string
    {
        return $this->DateFrom;
    }

    public function getDateTo(): string
    {
        return $this->DateTo;
    }

    public function getAccount(): string
    {
        return $this->Account;
    }

    public function getBank(): BankType
    {
        return $this->Bank;
    }

    public function getOpeningBalance(): ?float
    {
        return $this->OpeningBalance;
    }

    public function getTotalDebits(): ?float
    {
        return $this->TotalDebits;
    }

    public function getTotalCredits(): ?float
    {
        return $this->TotalCredits;
    }

    public function getClosingBalance(): float
    {
        return $this->ClosingBalance;
    }

    /**
     * @return OperationInfo[]
     */
    public function getOperationInfo(): array
    {
        return $this->OperationInfo;
    }

    public function getStamp(): Stamp
    {
        return $this->Stamp;
    }

}