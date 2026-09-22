<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Exceptions;


use TTBooking\DirectBank\Objects\ErrorType;

class ClientException extends \Exception
{
    protected ?ErrorType $error = null;

    public static function fromError(ErrorType $error): static
    {
        $exception = new static($error->getDescription(), (int) $error->getCode());
        $exception->error = $error;

        return $exception;
    }

    /**
     * Ошибка из ответа банка (ResultBank/Error), если она была
     */
    public function getError(): ?ErrorType
    {
        return $this->error;
    }

    /**
     * Код ошибки банка как есть, строкой из 4 символов
     */
    public function getBankCode(): ?string
    {
        return $this->error?->getCode();
    }
}
