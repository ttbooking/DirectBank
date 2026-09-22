<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use TTBooking\DirectBank\FormatVersion;

/**
 * Создаваемый документ получает версию формата по умолчанию (FormatVersion::getDefault()).
 * Маппер создаёт объекты без конструктора, поэтому разобранный документ сохраняет версию из XML.
 */
trait DefaultFormatVersion
{
    public function __construct()
    {
        $this->formatVersion = FormatVersion::getDefault();
    }
}
