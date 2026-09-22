<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


/**
 * Коды ошибок банковского сервиса
 *
 * @see https://github.com/1C-Company/DirectBank/blob/2.2.2/doc/common-section/tables.md#errors
 */
class ErrorCode
{
    // Раздел 10. Общие ошибки

    //Указанный сервис временно недоступен
    const SERVICE_UNAVAILABLE = '1001';
    //Не указан обязательный заголовок в запросе
    const HEADER_MISSING = '1002';
    //Идентификатор клиента не найден
    const CUSTOMER_NOT_FOUND = '1003';
    //Некорректный формат идентификатора клиента
    const INVALID_CUSTOMER_ID = '1004';
    //Неизвестный вендор клиентского ПО
    const UNKNOWN_VENDOR = '1005';
    //Сессия закрыта по тайм-ауту
    const SESSION_TIMEOUT = '1006';
    //Некорректный идентификатор сессии
    const INVALID_SESSION_ID = '1007';
    //Доступ к сервису заблокирован
    const ACCESS_BLOCKED = '1008';
    //Ошибка приемного сервиса
    const RECEIVING_SERVICE_ERROR = '1009';
    //Внутренняя ошибка
    const INTERNAL_ERROR = '1010';
    //Некорректный метод аутентификации клиента
    const INVALID_AUTH_METHOD = '1011';
    //Не удалось получить настройки обмена с банком
    const SETTINGS_UNAVAILABLE = '1012';
    //Недостаточно прав на операции с расч.счетом
    const ACCOUNT_ACCESS_DENIED = '1013';

    // Раздел 11. Ошибки при работе с электронной подписью

    //Сертификат электронной подписи не найден
    const CERTIFICATE_NOT_FOUND = '1101';
    //Неизвестный издатель сертификата электронной подписи
    const UNKNOWN_CERTIFICATE_ISSUER = '1102';
    //Сертификат электронной подписи отозван
    const CERTIFICATE_REVOKED = '1103';
    //Сертификат электронной подписи просрочен
    const CERTIFICATE_EXPIRED = '1104';
    //Сертификат электронной подписи не вступил в силу
    const CERTIFICATE_NOT_YET_VALID = '1105';
    //Нет доверия к сертификату электронной подписи
    const CERTIFICATE_UNTRUSTED = '1106';

    // Раздел 12. Ошибки аутентификации и авторизации

    //Некорректные данные для аутентификации
    const INVALID_CREDENTIALS = '1201';
    //Недостаточно прав у пользователя
    const INSUFFICIENT_PRIVILEGES = '1202';
    //Доступ к сервису временно заблокирован
    const ACCESS_TEMPORARILY_BLOCKED = '1203';
    //Некорректный ОТР
    const INVALID_OTP = '1204';
    //Услуга не доступна
    const SERVICE_NOT_CONNECTED = '1205';

    // Раздел 20. Ошибки обработки транспортного контейнера

    //Некорректный формат транспортного контейнера
    const INVALID_PACKET_FORMAT = '2001';
    //Дубль транспортного контейнера
    const DUPLICATE_PACKET = '2002';
    //Недопустимый размер транспортного контейнера
    const INVALID_PACKET_SIZE = '2003';
    //Неизвестный отправитель
    const UNKNOWN_SENDER = '2004';
    //Неизвестный получатель
    const UNKNOWN_RECIPIENT = '2005';
    //Недопустимая версия формата транспортного контейнера
    const UNSUPPORTED_PACKET_VERSION = '2006';
    //Недопустимый код вида электронного документа
    const UNSUPPORTED_DOC_KIND = '2007';
    //Недопустимая версия формата электронного документа
    const UNSUPPORTED_DOC_VERSION = '2008';
    //Некорректная дата формирования
    const INVALID_CREATION_DATE = '2009';
    //Невозможно расшифровать содержимое
    const DECRYPTION_FAILED = '2010';
    //Некорректное сжатие (архивирование)
    const DECOMPRESSION_FAILED = '2011';
    //Некорректное имя файла электронного документа
    const INVALID_FILE_NAME = '2012';
    //Отсутствует электронная подпись
    const SIGNATURE_MISSING = '2013';
    //Некорректная электронная подпись
    const INVALID_SIGNATURE = '2014';
    //Невозможно проверить электронную подпись
    const SIGNATURE_UNVERIFIABLE = '2015';

    // Раздел 21. Ошибки запроса транспортного контейнера

    //Некорректный формат отметки времени
    const INVALID_TIMESTAMP_FORMAT = '2101';
    //Транспортный контейнер не найден
    const PACKET_NOT_FOUND = '2102';

    // Раздел 22. Ошибки обработки электронных документов

    //Некорректный формат электронного документа
    const INVALID_DOC_FORMAT = '2201';
    //Дубль электронного документа по ИД
    const DUPLICATE_DOC_ID = '2202';
    //Дубль электронного документа по реквизитам
    const DUPLICATE_DOC_DETAILS = '2203';
    //Недопустимый размер электронного документа
    const INVALID_DOC_SIZE = '2204';
    //Ошибка в реквизитах электронного документа
    const INVALID_DOC_DETAILS = '2205';
    //Некорректный период запроса выписки
    const INVALID_STATEMENT_PERIOD = '2206';
    //Отсутствует электронный документ с запрашиваемым ИД
    const DOC_NOT_FOUND = '2207';

    //Ошибки, после которых требуется повторная аутентификация
    const REAUTHENTICATE = [
        self::SESSION_TIMEOUT,
        self::INVALID_SESSION_ID,
    ];
}
