<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class BankType
{
    /**
     * @name BIC
     */
    protected string $bic;
    /**
     * @name Name
     */
    protected ?string $name = null;
    /**
     * @name City
     */
    protected ?string $city = null;
    /**
     * @name CorrespAcc
     */
    protected ?string $correspAcc = null;

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @param  string  $bic
     * @return BankType
     */
    public function setBic(string $bic): BankType
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param  string|null  $name
     * @return BankType
     */
    public function setName(?string $name): BankType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param  string|null  $city
     * @return BankType
     */
    public function setCity(?string $city): BankType
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCorrespAcc(): ?string
    {
        return $this->correspAcc;
    }

    /**
     * @param  string|null  $correspAcc
     * @return BankType
     */
    public function setCorrespAcc(?string $correspAcc): BankType
    {
        $this->correspAcc = $correspAcc;
        return $this;
    }
}