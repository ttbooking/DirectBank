<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


/**
 * Коды видов электронных документов
 *
 * @see https://github.com/1C-Company/DirectBank/blob/2.2.2/doc/common-section/tables.md#ed
 */
class DocKind
{
    //Извещение о состоянии обработки транспортного контейнера (1С <-- Банк)
    const STATUS_PACKET_NOTICE = '01';
    const SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION = self::STATUS_PACKET_NOTICE;
    //Извещение о состоянии электронного документа (1С <-- Банк), обязательный
    const STATUS_DOC_NOTICE = '02';
    //Запрос о состоянии электронного документа (1С --> Банк), обязательный
    const STATUS_REQUEST = '03';
    //Запрос об отзыве электронного документа (1С --> Банк)
    const CANCELATION_REQUEST = '04';
    //Запрос-зонд (1С --> Банк), обязательный
    const PROBE = '05';
    //Настройки обмена с банком (1С <-- Банк), обязательный
    const SETTINGS = '06';

    //Платежное поручение (1С --> Банк)
    const PAY_DOC_RU = '10';
    //Платежное требование (1С --> Банк)
    const PAY_REQUEST = '11';
    //Инкассовое поручение (в выписке)
    const COLLECTION_ORDER = '12';
    //Внутренний банковский документ (в выписке)
    const INNER_DOC = '13';
    //Запрос выписки банка (1С --> Банк)
    const BANK_STATEMENT_REQUEST = '14';
    //Выписка банка (1С <-- Банк)
    const BANK_STATEMENT = '15';
    //Мемориальный ордер (в выписке)
    const MEM_ORDER = '16';
    //Платежный ордер (в выписке)
    const PAYMENT_ORDER = '17';
    //Банковский ордер (в выписке)
    const BANK_ORDER = '18';

    //Список на открытие счетов по зарплатному проекту (1С --> Банк)
    const WAGES_ACCOUNT_OPENING_LIST = '19';
    //Подтверждение открытия счетов по зарплатному проекту (1С <-- Банк)
    const WAGES_ACCOUNT_OPENING_CONFIRMATION = '20';
    //Список на зачисление денежных средств на счета сотрудников (1С --> Банк)
    const WAGES_CREDIT_LIST = '21';
    //Подтверждение зачисления денежных средств на счета сотрудников (1С <-- Банк)
    const WAGES_CREDIT_CONFIRMATION = '22';
    //Список уволенных сотрудников (1С --> Банк)
    const WAGES_DISMISSED_LIST = '23';

    //Объявление на взнос наличными (в выписке)
    const CASH_CONTRIBUTION = '24';
    //Денежный чек (в выписке)
    const CHECK = '25';

    //Поручение на перевод валюты (1С --> Банк)
    const CURRENCY_TRANSFER_ORDER = '30';
    //Выписка по валютному счету (1С <-- Банк)
    const CURRENCY_STATEMENT = '35';

    //Обязательные виды электронных документов
    const REQUIRED = [
        self::STATUS_DOC_NOTICE,
        self::STATUS_REQUEST,
        self::PROBE,
        self::SETTINGS,
    ];
}
