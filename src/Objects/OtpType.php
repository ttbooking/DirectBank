<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Параметры доп. аутентификации по OTP (LogonResponse/ExtraAuth/OTP)
 */
class OtpType
{
    /**
     * @xmlAttribute
     */
    protected ?string $phoneMask = null;

    /**
     * @xmlAttribute
     */
    protected ?string $code = null;

    /**
     * Маска телефона или номер клиента
     */
    public function getPhoneMask(): ?string
    {
        return $this->phoneMask;
    }

    /**
     * Короткий код сессии, который показывается пользователю при вводе OTP
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
}
