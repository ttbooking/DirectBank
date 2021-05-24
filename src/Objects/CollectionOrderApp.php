<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * Данные инкассового поручения
 * @see \TTBooking\DirectBank\Dictionary\DocKind::COLLECTION_ORDER
 */
class CollectionOrderApp extends PaymentDataType
{
    protected ?BudgetPaymentInfoType $BudgetPaymentInfo = null;
}