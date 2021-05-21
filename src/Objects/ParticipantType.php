<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class ParticipantType
{
    /**
     * @name Customer
     */
    protected ?CustomerPartyType $customer = null;

    /**
     * @name Bank
     */
    protected ?BankPartyType $bank = null;

    /**
     * @return \TTBooking\DirectBank\Objects\CustomerPartyType|null
     */
    public function getCustomer(): ?CustomerPartyType
    {
        return $this->customer;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\CustomerPartyType|null  $customer
     * @return ParticipantType
     */
    public function setCustomer(?CustomerPartyType $customer): ParticipantType
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return \TTBooking\DirectBank\Objects\BankPartyType|null
     */
    public function getBank(): ?BankPartyType
    {
        return $this->bank;
    }

    /**
     * @param  \TTBooking\DirectBank\Objects\BankPartyType|null  $bank
     * @return ParticipantType
     */
    public function setBank(?BankPartyType $bank): ParticipantType
    {
        $this->bank = $bank;
        return $this;
    }
}