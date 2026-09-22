<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Данные дайджеста (Digest/Data): base64 с версией алгоритма
 */
class DigestDataType
{
    /**
     * @xmlNodeValue
     */
    protected string $value;

    /**
     * @xmlAttribute
     */
    protected string $algorithmVersion;

    public function __construct(string $value, string $algorithmVersion)
    {
        $this->value = $value;
        $this->algorithmVersion = $algorithmVersion;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getAlgorithmVersion(): string
    {
        return $this->algorithmVersion;
    }
}
