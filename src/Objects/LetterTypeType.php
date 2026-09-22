<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Тип письма (Settings/Data/Letters/LetterType)
 */
class LetterTypeType
{
    //Код типа письма, до 2 символов
    protected string $Code;

    //Наименование типа письма
    protected string $Name;

    public function getCode(): string
    {
        return $this->Code;
    }

    public function getName(): string
    {
        return $this->Name;
    }
}
