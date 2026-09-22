<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Настройки криптографии (Settings/Data/CryptoParameters)
 */
class CryptoParametersType
{
    //Имя CSP (cryptographic service provider)
    protected string $CSPName;

    //Тип CSP
    protected int $CSPType;

    //Алгоритм подписи, например, GOST R 34.10-2001
    protected string $SignAlgorithm;

    //Алгоритм хэширования, например, GOST R 34.11-94
    protected string $HashAlgorithm;

    //Применение шифрования данных на прикладном уровне
    protected ?CryptoEncryptedType $Encrypted = null;

    //Доверенный корневой сертификат УЦ банка, base64
    protected ?string $BankTrustedRootCertificate = null;

    //Сертификат электронной подписи банка, base64
    protected ?string $BankCertificate = null;

    //Карточка электронных подписей клиента
    protected CustomerSignatureType $CustomerSignature;

    //Адрес, откуда загружается файл описания внешнего модуля
    protected ?string $URLAddinInfo = null;

    public function getCSPName(): string
    {
        return $this->CSPName;
    }

    public function getCSPType(): int
    {
        return $this->CSPType;
    }

    public function getSignAlgorithm(): string
    {
        return $this->SignAlgorithm;
    }

    public function getHashAlgorithm(): string
    {
        return $this->HashAlgorithm;
    }

    public function getEncrypted(): ?CryptoEncryptedType
    {
        return $this->Encrypted;
    }

    public function getBankTrustedRootCertificate(): ?string
    {
        return $this->BankTrustedRootCertificate;
    }

    public function getBankCertificate(): ?string
    {
        return $this->BankCertificate;
    }

    public function getCustomerSignature(): CustomerSignatureType
    {
        return $this->CustomerSignature;
    }

    public function getURLAddinInfo(): ?string
    {
        return $this->URLAddinInfo;
    }
}
