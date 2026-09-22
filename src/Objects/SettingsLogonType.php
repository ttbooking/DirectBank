<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Способ аутентификации на ресурсе банка (Settings/Data/Logon): логин или сертификат
 */
class SettingsLogonType
{
    protected ?SettingsLoginType $Login = null;

    protected ?SettingsCertificateLogonType $Certificate = null;

    /**
     * Аутентификация по логину и паролю
     */
    public function getLogin(): ?SettingsLoginType
    {
        return $this->Login;
    }

    /**
     * Аутентификация по сертификату электронной подписи
     */
    public function getCertificate(): ?SettingsCertificateLogonType
    {
        return $this->Certificate;
    }
}
