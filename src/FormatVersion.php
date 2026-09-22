<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;


use TTBooking\DirectBank\Dictionary\DefaultValue;

/**
 * Версия формата обмена по умолчанию: для заголовка APIVersion клиента и атрибута formatVersion
 * создаваемых документов. Разобранные документы сохраняют версию из XML.
 *
 * Для банка, который поддерживает только 2.2.x: FormatVersion::setDefault('2.2.2').
 */
final class FormatVersion
{
    //Последняя версия стандарта, которую поддерживает библиотека
    const LATEST = DefaultValue::FORMAT_VERSION;

    //Версии стандарта, которые поддерживает библиотека
    const SUPPORTED = ['2.2.2', '2.3.2'];

    private static string $default = self::LATEST;

    public static function getDefault(): string
    {
        return self::$default;
    }

    public static function setDefault(string $version): void
    {
        if (! in_array($version, self::SUPPORTED, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Unsupported format version "%s", supported: %s.', $version, implode(', ', self::SUPPORTED)
            ));
        }

        self::$default = $version;
    }
}
