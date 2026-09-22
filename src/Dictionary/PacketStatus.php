<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


/**
 * Коды статусов транспортных контейнеров
 *
 * @see https://github.com/1C-Company/DirectBank/blob/2.2.2/doc/common-section/tables.md#packet
 */
class PacketStatus
{
    //Принят: транспортный контейнер прошел первичный контроль и поступил в обработку
    const ACCEPTED = '01';
}
