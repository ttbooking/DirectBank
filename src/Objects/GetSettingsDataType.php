<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Настройки обмена в ответе на GetSettings (GetSettingsResponse/Data): base64 с видом ЭД
 */
class GetSettingsDataType
{
    /**
     * @xmlNodeValue
     */
    protected string $value;

    /**
     * @xmlAttribute
     */
    protected string $dockind;

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDockind(): string
    {
        return $this->dockind;
    }
}
