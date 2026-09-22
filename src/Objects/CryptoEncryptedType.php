<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Применение шифрования данных на прикладном уровне (Settings/Data/CryptoParameters/Encrypted)
 */
class CryptoEncryptedType
{
    //Алгоритм шифрования, например, GOST 28147-89
    protected string $EncryptAlgorithm;

    public function getEncryptAlgorithm(): string
    {
        return $this->EncryptAlgorithm;
    }
}
