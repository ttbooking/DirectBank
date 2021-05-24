<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * @see \TTBooking\DirectBank\Dictionary\DocKind::MEM_ORDER
 */
class MemOrderApp
{
    //Номер документа (поле 3).
    protected string $DocNo;
    //Дата составления (поле 4).
    protected string $DocDate;
    //Свободное поле (поле 5)
    protected ?string $SpareField5 = null;
    //Составитель (поле 6).
    protected ?AuthorType $Author = null;
    //Наименование счета по дебету (поле 7)
    protected ?string $AccountNameDebit = null;
    //Счет по дебету (поле 8)
    protected ?string $AccountDebit = null;
    //Сумма документа (поле 9).
    protected float $Sum;
    //Свободное поле (поле 9a)
    protected ?string $SpareField9a = null;
    //Наименование счета по кредиту (поле 10)
    protected ?string $AccountNameCredit = null;
    //Счет по кредиту (поле 11)
    protected ?string $AccountCredit = null;
    //Шифр документа (поле 13).
    protected ?string $PartialTransitionKind = null;
    //Свободное поле (поле 14)
    protected ?string $SpareField14 = null;
    //Свободное поле (поле 15)
    protected ?string $SpareField15 = null;
    //Содержание операции (поле 16).
    protected ?string $TransitionContent = null;
    //Свободное поле (поле 20)
    protected ?string $SpareField20 = null;

    public function getDocNo(): string
    {
        return $this->DocNo;
    }

    public function getDocDate(): string
    {
        return $this->DocDate;
    }

    public function getSpareField5(): ?string
    {
        return $this->SpareField5;
    }

    public function getAuthor(): ?AuthorType
    {
        return $this->Author;
    }

    public function getAccountNameDebit(): ?string
    {
        return $this->AccountNameDebit;
    }

    public function getAccountDebit(): ?string
    {
        return $this->AccountDebit;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

    public function getSpareField9a(): ?string
    {
        return $this->SpareField9a;
    }

    public function getAccountNameCredit(): ?string
    {
        return $this->AccountNameCredit;
    }

    public function getAccountCredit(): ?string
    {
        return $this->AccountCredit;
    }

    public function getPartialTransitionKind(): ?string
    {
        return $this->PartialTransitionKind;
    }

    public function getSpareField14(): ?string
    {
        return $this->SpareField14;
    }

    public function getSpareField15(): ?string
    {
        return $this->SpareField15;
    }

    public function getTransitionContent(): ?string
    {
        return $this->TransitionContent;
    }

    public function getSpareField20(): ?string
    {
        return $this->SpareField20;
    }
}