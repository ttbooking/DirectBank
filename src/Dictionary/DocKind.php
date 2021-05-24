<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


class DocKind
{
    //Извещение о состоянии обработки транспортного контейнера (1С <-- Банк)
    const SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION = '01';
    //Платежное поручение (1С --> Банк, в выписке)
    const PAY_DOC_RU = '10';
    //Платежное требование (1С --> Банк, в выписке)
    const PAY_REQUEST = '11';
    //Инкассовое поручение (в выписке)
    const COLLECTION_ORDER = '12';
    //Внутренний банковский документ (в выписке)
    const INNER_DOC = '13';
    //Запрос выписки банка (1С <-- Банк)
    const BANK_STATEMENT_REQUEST = '14';
    //Мемориальный ордер (в выписке)
    const MEM_ORDER = '16';
    //Платежный ордер (в выписке)
    const PAYMENT_ORDER = '17';
    //Банковский ордер (в выписке)
    const BANK_ORDER = '18';
    //Выписка банка (1С <-- Банк)
    const BANK_STATEMENT = '15';
    //Объявление на взнос наличными
    const CASH_CONTRIBUTION = '24';
    //Денежный чек
    const CHECK = '25';

}