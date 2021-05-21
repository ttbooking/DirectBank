<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class BankPartyType
{
    /**
     * @xmlAttribute
     */
    protected string $bic;
    /**
     * @xmlAttribute
     */
    protected ?string $name = null;

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @param  string  $bic
     * @return BankPartyType
     */
    public function setBic(string $bic): BankPartyType
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
     * @return BankPartyType
     */
    public function setName(?string $name): BankPartyType
    {
        $this->name = $name;
        return $this;
    }
}