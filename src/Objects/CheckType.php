<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class CheckType extends OtherPaymentDataType
{
    protected ?PersonType $Person = null;

    protected ?DataPrintingType $DataPrinting = null;

    protected ?DetailsType $Details = null;

    public function getPerson(): ?PersonType
    {
        return $this->Person;
    }

    public function getDataPrinting(): ?DataPrintingType
    {
        return $this->DataPrinting;
    }

    public function getDetails(): ?DetailsType
    {
        return $this->Details;
    }
}