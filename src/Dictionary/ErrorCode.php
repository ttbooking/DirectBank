<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


class ErrorCode
{
    //Сессия закрыта по тайм-ауту
    const SESSION_TIMEOUT = '1006';
    //Некорректный идентификатор сессии
    const INVALID_SESSION_ID = '1007';

    //Ошибки, после которых требуется повторная аутентификация
    const REAUTHENTICATE = [
        self::SESSION_TIMEOUT,
        self::INVALID_SESSION_ID,
    ];
}
