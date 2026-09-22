<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class LogonResponseType
{
    protected string $SID;

    protected ?ExtraAuthType $ExtraAuth = null;

    /**
     * @return string
     */
    public function getSID(): string
    {
        return $this->SID;
    }

    public function getExtraAuth(): ?ExtraAuthType
    {
        return $this->ExtraAuth;
    }

    /**
     * Банк требует подтвердить вход одноразовым паролем: SID ещё не авторизован.
     * OTP — единственный вариант ExtraAuth, а пустой <OTP/> маппер не разбирает,
     * поэтому признаком служит сам ExtraAuth.
     */
    public function isOtpRequired(): bool
    {
        return $this->ExtraAuth !== null;
    }
}
