<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class CustomerPartyType
{
    /**
     * @xmlAttribute
     */
    protected string $id;
    /**
     * @xmlAttribute
     */
    protected ?string $name = null;
    /**
     * @xmlAttribute
     */
    protected ?string $inn = null;
    /**
     * @xmlAttribute
     */
    protected ?string $kpp = null;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string  $id
     * @return CustomerPartyType
     */
    public function setId(string $id): CustomerPartyType
    {
        $this->id = $id;
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
     * @return CustomerPartyType
     */
    public function setName(?string $name): CustomerPartyType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }

    /**
     * @param  string|null  $inn
     * @return CustomerPartyType
     */
    public function setInn(?string $inn): CustomerPartyType
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    /**
     * @param  string|null  $kpp
     * @return CustomerPartyType
     */
    public function setKpp(?string $kpp): CustomerPartyType
    {
        $this->kpp = $kpp;
        return $this;
    }
}