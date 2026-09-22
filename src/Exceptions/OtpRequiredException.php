<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Exceptions;


use TTBooking\DirectBank\Objects\LogonResponseType;
use TTBooking\DirectBank\Objects\OtpType;

/**
 * Банк требует подтвердить вход одноразовым паролем (OTP), отправленным клиенту.
 * Подтверждение: Client::confirmOtp($exception->getSessionId(), $otp).
 */
class OtpRequiredException extends ClientException
{
    protected string $sessionId;

    protected ?OtpType $otp = null;

    public static function fromLogonResponse(LogonResponseType $response): static
    {
        $exception = new static('Bank requires one-time password (OTP) to complete Logon.');
        $exception->sessionId = $response->getSID();
        $exception->otp = $response->getExtraAuth()?->getOTP();

        return $exception;
    }

    /**
     * Идентификатор неавторизованной сессии
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * Маска телефона или номер клиента
     */
    public function getPhoneMask(): ?string
    {
        return $this->otp?->getPhoneMask();
    }

    /**
     * Короткий код сессии, который показывается пользователю при вводе OTP
     */
    public function getSessionCode(): ?string
    {
        return $this->otp?->getCode();
    }
}
