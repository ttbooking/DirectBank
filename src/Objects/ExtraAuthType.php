<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Дополнительная аутентификация (LogonResponse/ExtraAuth)
 */
class ExtraAuthType
{
    protected ?OtpType $OTP = null;

    public function getOTP(): ?OtpType
    {
        return $this->OTP;
    }
}
