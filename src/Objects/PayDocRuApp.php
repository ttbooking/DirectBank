<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * Данные платежного поручения
 * @see \TTBooking\DirectBank\Dictionary\DocKind::PAY_DOC_RU
 */
class PayDocRuApp extends PaymentDataType
{
    protected ?BudgetPaymentInfoType $BudgetPaymentInfo = null;

    public function getBudgetPaymentInfo(): ?BudgetPaymentInfoType
    {
        return $this->BudgetPaymentInfo;
    }
}