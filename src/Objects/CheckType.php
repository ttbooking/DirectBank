<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class CheckType extends OtherPaymentDataType
{
    protected ?PersonType $Person = null;

    protected ?DataPrintingType $DataPrinting = null;

    /**
     * @var DetailsType[]
     */
    protected array $Details = [];

    public function getPerson(): ?PersonType
    {
        return $this->Person;
    }

    public function getDataPrinting(): ?DataPrintingType
    {
        return $this->DataPrinting;
    }

    /**
     * @return DetailsType[]
     */
    public function getDetails(): array
    {
        return $this->Details;
    }
}
