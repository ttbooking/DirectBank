<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * @xmlRoot PayDoc
 * @xmlEncoding utf-8
 */
class PayDoc
{
    /**
     * @xmlAttribute
     */
    protected string $id;

    /**
     * @xmlAttribute
     */
    protected string $docKind;

    //Данные платежного поручения
    protected ?PayDocRuApp $PayDocRu = null;

    //Данные платежного требования
    protected ?PayRequestApp $PayRequest = null;

    //Данные инкассового поручения
    protected ?CollectionOrderApp $CollectionOrder = null;

    //Данные платежного ордера
    protected ?PaymentOrder $PaymentOrderApp = null;

    //Данные банковского ордера
    protected ?BankOrderApp $BankOrder = null;

    //Данные мемориального ордера
    protected ?MemOrderApp $MemOrder = null;

    //Данные внутр.банковского документа
    protected ?OtherPaymentDataType $InnerDoc = null;

    //Данные объявления на взнос наличными
    protected ?CashContributionType $CashContribution = null;

    //Данные денежного чека
    protected ?CheckType $Check = null;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDocKind(): string
    {
        return $this->docKind;
    }

    public function getPayDocRu(): ?PayDocRuApp
    {
        return $this->PayDocRu;
    }

    public function getPayRequest(): ?PayRequestApp
    {
        return $this->PayRequest;
    }

    public function getCollectionOrder(): ?CollectionOrderApp
    {
        return $this->CollectionOrder;
    }

    public function getPaymentOrderApp(): ?PaymentOrder
    {
        return $this->PaymentOrderApp;
    }

    public function getBankOrder(): ?BankOrderApp
    {
        return $this->BankOrder;
    }

    public function getMemOrder(): ?MemOrderApp
    {
        return $this->MemOrder;
    }

    public function getInnerDoc(): ?OtherPaymentDataType
    {
        return $this->InnerDoc;
    }

    public function getCashContribution(): ?CashContributionType
    {
        return $this->CashContribution;
    }

    public function getCheck(): ?CheckType
    {
        return $this->Check;
    }

}