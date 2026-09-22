<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Аутентификация по сертификату электронной подписи (Settings/Data/Logon/Certificate)
 */
class SettingsCertificateLogonType
{
    //Алгоритм шифрования, например, GOST 28147-89
    protected string $EncryptingAlgorithm;

    public function getEncryptingAlgorithm(): string
    {
        return $this->EncryptingAlgorithm;
    }
}
