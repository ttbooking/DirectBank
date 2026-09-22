<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Exceptions;


use Psr\Http\Message\ResponseInterface;

/**
 * Ответ банка не удалось разобрать как ResultBank или в нём нет ожидаемых данных
 */
class UnexpectedResponseException extends ClientException
{
    protected ?ResponseInterface $response = null;

    public static function fromResponse(string $message, ResponseInterface $response, ?\Throwable $previous = null): static
    {
        $exception = new static($message, $response->getStatusCode(), $previous);
        $exception->response = $response;

        return $exception;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
