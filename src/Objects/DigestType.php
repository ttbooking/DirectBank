<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Дайджест документа (Digest). Формируется внешней компонентой банка перед подписью.
 */
class DigestType
{
    /**
     * @name Data
     */
    protected DigestDataType $data;

    /**
     * @param string $data данные дайджеста в base64
     * @param string $algorithmVersion версия алгоритма формирования дайджеста
     */
    public function __construct(string $data, string $algorithmVersion)
    {
        $this->data = new DigestDataType($data, $algorithmVersion);
    }

    /**
     * Данные дайджеста в base64
     */
    public function getData(): string
    {
        return $this->data->getValue();
    }

    public function getAlgorithmVersion(): string
    {
        return $this->data->getAlgorithmVersion();
    }
}
