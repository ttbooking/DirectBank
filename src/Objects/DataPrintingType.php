<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * Данные бумажной формы чека
 */
class DataPrintingType
{
    //Серия чека
    protected ?string $CheckSeries = null;
    //Номер чека
    protected ?string $CheckNumber = null;

    public function getCheckSeries(): ?string
    {
        return $this->CheckSeries;
    }

    public function getCheckNumber(): ?string
    {
        return $this->CheckNumber;
    }
}