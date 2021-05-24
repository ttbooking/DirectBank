<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * Реквизиты бюджетного документа.  См.правила заполнения платежных поручений,
 * утвержденные приказом Минфина России от 12 ноября 2013 года № 107н.
 */
class BudgetPaymentInfoType
{
    protected ?string $DrawerStatus = null;
    protected ?string $CBC = null;
    protected ?string $OKTMO = null;
    protected ?string $Reason = null;
    protected ?string $TaxPeriod = null;
    protected ?string $DocNo = null;
    protected ?string $DocDate = null;
    protected ?string $PayType = null;

    /**
     * @return string|null
     */
    public function getDrawerStatus(): ?string
    {
        return $this->DrawerStatus;
    }

    /**
     * @return string|null
     */
    public function getCBC(): ?string
    {
        return $this->CBC;
    }

    /**
     * @return string|null
     */
    public function getOKTMO(): ?string
    {
        return $this->OKTMO;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->Reason;
    }

    /**
     * @return string|null
     */
    public function getTaxPeriod(): ?string
    {
        return $this->TaxPeriod;
    }

    /**
     * @return string|null
     */
    public function getDocNo(): ?string
    {
        return $this->DocNo;
    }

    /**
     * @return string|null
     */
    public function getDocDate(): ?string
    {
        return $this->DocDate;
    }

    /**
     * @return string|null
     */
    public function getPayType(): ?string
    {
        return $this->PayType;
    }
}