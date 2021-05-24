<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;

/**
 * Направления и суммы выдачи
 */
class DetailsType
{
    //Указываются цифрами символы, предусмотренные отчетностью по форме 0409202,
    //в соответствии с Указанием Банка России N 2332-У
    protected string $Symbol;
    //Указываются направления (цели) выдачи наличных денег в соответствии
    //с содержанием символов отчетности по форме 0409202 и содержанием операции
    protected ?string $Purpose;
    //Сумма расходов
    protected float $Sum;

    public function getSymbol(): string
    {
        return $this->Symbol;
    }

    public function getPurpose(): ?string
    {
        return $this->Purpose;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

}