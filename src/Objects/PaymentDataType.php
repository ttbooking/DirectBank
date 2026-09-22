<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class PaymentDataType
{
    //Номер документа (поле 3).
    protected string $DocNo;
    //Дата составления (поле 4).
    protected string $DocDate;
    //Сумма документа (поле 7).
    protected float $Sum;
    //Плательщик (поля 8, 9, 10, 11, 12, 60, 102).
    protected CustomerDetailsType $Payer;
    //Получатель (поля 13, 14, 15, 16, 17, 61, 103).
    protected CustomerDetailsType $Payee;
    //Вид платежа (поле 5). Указывается "срочно",  "телеграфом",  "почтой",
    //иное значение в порядке, установленном  банком.
    protected ?string $PaymentKind = null;
    //Вид операции (поле 18). Указывается условное цифровое обозначение документа, согласно установленного
    // ЦБР перечня условных обозначений (шифров) документов, проводимых по счетам в кредитных организациях.
    protected ?string $TransitionKind = null;
    //Очередность платежа (поле 21).
    protected ?string $Priority = null;
    //Уникальный идентификатор платежа (поле 22). С 31 марта 2014 года согласно Указанию N 3025-У ЦБР.
    protected ?string $Code = null;
    //Код вида дохода (поле 20) согласно Указанию 5286-У ЦБРФ
    protected ?string $IncomeTypeCode = null;
    //Назначение платежа (поле 24).
    protected ?string $Purpose = null;

    public function getDocNo(): string
    {
        return $this->DocNo;
    }

    public function getDocDate(): string
    {
        return $this->DocDate;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

    public function getPayer(): CustomerDetailsType
    {
        return $this->Payer;
    }

    public function getPayee(): CustomerDetailsType
    {
        return $this->Payee;
    }

    public function getPaymentKind(): ?string
    {
        return $this->PaymentKind;
    }

    public function getTransitionKind(): ?string
    {
        return $this->TransitionKind;
    }

    public function getPriority(): ?string
    {
        return $this->Priority;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function getIncomeTypeCode(): ?string
    {
        return $this->IncomeTypeCode;
    }

    public function getPurpose(): ?string
    {
        return $this->Purpose;
    }

    public function setDocNo(string $docNo): static
    {
        $this->DocNo = $docNo;
        return $this;
    }

    public function setDocDate(string $docDate): static
    {
        $this->DocDate = $docDate;
        return $this;
    }

    public function setSum(float $sum): static
    {
        $this->Sum = $sum;
        return $this;
    }

    public function setPayer(CustomerDetailsType $payer): static
    {
        $this->Payer = $payer;
        return $this;
    }

    public function setPayee(CustomerDetailsType $payee): static
    {
        $this->Payee = $payee;
        return $this;
    }

    public function setPaymentKind(?string $paymentKind): static
    {
        $this->PaymentKind = $paymentKind;
        return $this;
    }

    public function setTransitionKind(?string $transitionKind): static
    {
        $this->TransitionKind = $transitionKind;
        return $this;
    }

    public function setPriority(?string $priority): static
    {
        $this->Priority = $priority;
        return $this;
    }

    public function setCode(?string $code): static
    {
        $this->Code = $code;
        return $this;
    }

    public function setIncomeTypeCode(?string $incomeTypeCode): static
    {
        $this->IncomeTypeCode = $incomeTypeCode;
        return $this;
    }

    public function setPurpose(?string $purpose): static
    {
        $this->Purpose = $purpose;
        return $this;
    }
}
