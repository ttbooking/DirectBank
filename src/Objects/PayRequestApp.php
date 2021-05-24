<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * Данные платежного требования
 * @see \TTBooking\DirectBank\Dictionary\DocKind::PAY_REQUEST
 */
class PayRequestApp extends PaymentDataType
{
    //Условие оплаты (поле 35):
    //1 - заранее данный акцепт плательщика;
    //2 - требуется получение акцепта плательщика.
    protected string $PaymentCondition;
    //Срок для акцепта (поле 36): количество дней.
    protected ?string $AcceptTerm;
    //Дата отсылки (вручения) плательщику предусмотренных договором документов (поле 37).
    protected ?string $DocDispatchDate;

    /**
     * @return string
     */
    public function getPaymentCondition(): string
    {
        return $this->PaymentCondition;
    }

    /**
     * @return string|null
     */
    public function getAcceptTerm(): ?string
    {
        return $this->AcceptTerm;
    }

    /**
     * @return string|null
     */
    public function getDocDispatchDate(): ?string
    {
        return $this->DocDispatchDate;
    }

}