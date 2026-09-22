<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class ErrorType
{
    /**
     * @var string
     */
    protected string $Code;

    protected string $Description;

    protected ?string $MoreInfo = null;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->Code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->Description;
    }

    /**
     * @return string|null
     */
    public function getMoreInfo(): ?string
    {
        return $this->MoreInfo;
    }
}