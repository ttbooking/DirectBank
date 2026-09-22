<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Параметры обмена (Settings/Data)
 */
class SettingsDataType
{
    //Уникальный идентификатор организации в банке
    protected string $CustomerID;

    //Адрес ресурса банка
    protected string $BankServerAddress;

    //Актуальная версия формата обмена данными
    protected string $FormatVersion;

    //Кодировка файлов обмена
    protected ?string $Encoding = null;

    //Признак сжатия электронных документов при обмене
    protected ?bool $Compress = null;

    //Способ аутентификации на ресурсе банка
    protected SettingsLogonType $Logon;

    protected ?CryptoParametersType $CryptoParameters = null;

    /**
     * Виды электронных документов, которыми возможен обмен с банком
     *
     * @var SettingsDocumentType[]
     */
    protected array $Document = [];

    //Параметры получения выписки в автоматическом режиме
    protected ?ReceiptStatementType $ReceiptStatement = null;

    //Свойства писем, с версии 2.3.1
    protected ?LettersType $Letters = null;

    public function getCustomerID(): string
    {
        return $this->CustomerID;
    }

    public function getBankServerAddress(): string
    {
        return $this->BankServerAddress;
    }

    public function getFormatVersion(): string
    {
        return $this->FormatVersion;
    }

    public function getEncoding(): string
    {
        return $this->Encoding ?? 'UTF-8';
    }

    public function isCompress(): bool
    {
        return $this->Compress ?? false;
    }

    public function getLogon(): SettingsLogonType
    {
        return $this->Logon;
    }

    public function getCryptoParameters(): ?CryptoParametersType
    {
        return $this->CryptoParameters;
    }

    /**
     * @return SettingsDocumentType[]
     */
    public function getDocuments(): array
    {
        return $this->Document;
    }

    /**
     * Коды видов электронных документов, которыми возможен обмен с банком
     *
     * @return string[]
     */
    public function getDocKinds(): array
    {
        return array_map(fn(SettingsDocumentType $document) => $document->getDocKind(), $this->Document);
    }

    public function getReceiptStatement(): ?ReceiptStatementType
    {
        return $this->ReceiptStatement;
    }

    public function getLetters(): ?LettersType
    {
        return $this->Letters;
    }
}
