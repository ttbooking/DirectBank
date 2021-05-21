<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use Traits\MappableTrait;

class ResultBank
{
    use MappableTrait;

    /**
     * @xmlAttribute
     */
    protected string $formatVersion;

    /**
     * @xmlAttribute
     */
    protected ?string $userAgent = null;

    protected ?ErrorType $Error = null;

    protected ?SuccessResultType $Success = null;

    public function getSuccess(): SuccessResultType
    {
        return $this->Success;
    }

    public function getError(): ?ErrorType
    {
        return $this->Error;
    }

    public function getFormatVersion(): string
    {
        return $this->formatVersion;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }
}