<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


/**
 * Коды статусов электронных документов
 *
 * @see https://github.com/1C-Company/DirectBank/blob/2.3.2/doc/common-section/tables.md#status
 */
class DocStatus
{
    //Принят: электронный документ прошел первичный контроль и поступил в обработку
    const ACCEPTED = '01';
    //Исполнен: платежный документ исполнен банком
    const EXECUTED = '02';
    //Отклонен банком: платеж не удалось исполнить, запрос не удалось выполнить
    const REJECTED = '03';
    //Приостановлен: платежный документ отложен банком из-за недостатка средств на счете
    const SUSPENDED = '04';
    //Аннулирован: электронный документ отозван клиентом с одобрения банка
    const CANCELED = '05';
    //Не подтвержден: платежный документ ожидает подтверждения по SMS или в личном кабинете
    const NOT_CONFIRMED = '06';
}
