<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


class DefaultValue
{
    const FORMAT_VERSION = '2.3.2';

    //UTF-8 BOM: с версии 2.3.x транспортный контейнер и файлы вложений всегда передаются с ним
    const BOM = "\xEF\xBB\xBF";

    //Формат отметки времени в GetPackList: dd.MM.yyyy HH:mm:ss
    const TIMESTAMP_FORMAT = 'd.m.Y H:i:s';
}