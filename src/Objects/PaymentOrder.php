<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class PaymentOrder extends PaymentDataType
{
    //Содержание операции (поле 70).
    protected ?string $TransitionContent = null;
    //Номер частичного платежа (поле 38).
    protected ?string $PartialPaymentNo = null;
    //Шифр платежного документа (поле 39).
    protected ?string $PartialTransitionKind = null;
    //Сумма остатка платежа (поле 42).
    protected ?string $SumResidualPayment = null;
    //Номер платежного документа (поле 40).
    protected ?string $PartialDocNo = null;
    //Дата платежного документа (поле 41).
    protected ?string $PartialDocDate = null;

    protected ?BudgetPaymentInfoType $BudgetPaymentInfo;

}