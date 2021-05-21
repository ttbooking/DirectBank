<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class ErrorType
{
    protected int $Code;

    protected string $Description;

    protected ?string $MoreInfo = null;

    /**
     * @return string
     */
    public function getCode(): int
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