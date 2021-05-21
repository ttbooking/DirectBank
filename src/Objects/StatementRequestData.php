<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class StatementRequestData
{
    /**
     * @name StatementType
     */
    protected int $statementType;
    /**
     * @name DateFrom
     */
    protected string $dateFrom;
    /**
     * @name DateTo
     */
    protected string $dateTo;
    /**
     * @name Account
     */
    protected string $account;
    /**
     * @name Bank
     */
    protected BankType $bank;

    /**
     * @return int
     */
    public function getStatementType(): int
    {
        return $this->statementType;
    }

    /**
     * @param  int  $statementType
     * @return StatementRequestData
     */
    public function setStatementType(int $statementType): StatementRequestData
    {
        $this->statementType = $statementType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    /**
     * @param  string  $dateFrom
     * @return StatementRequestData
     */
    public function setDateFrom(string $dateFrom): StatementRequestData
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    /**
     * @param  string  $dateTo
     * @return StatementRequestData
     */
    public function setDateTo(string $dateTo): StatementRequestData
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @param  string  $account
     * @return StatementRequestData
     */
    public function setAccount(string $account): StatementRequestData
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return \TTBooking\DirectBank\Objects\BankType
     */
    public function getBank(): BankType
    {
        return $this->bank;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\BankType  $bank
     * @return StatementRequestData
     */
    public function setBank(BankType $bank): StatementRequestData
    {
        $this->bank = $bank;
        return $this;
    }
}