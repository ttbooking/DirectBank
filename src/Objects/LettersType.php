<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Свойства писем (Settings/Data/Letters), с версии 2.3.1
 */
class LettersType
{
    //Максимальный суммарный объём присоединённых файлов в байтах
    protected int $AttachmentsLimit;

    /**
     * Возможные типы писем
     *
     * @var LetterTypeType[]
     */
    protected array $LetterType = [];

    public function getAttachmentsLimit(): int
    {
        return $this->AttachmentsLimit;
    }

    /**
     * @return LetterTypeType[]
     */
    public function getLetterTypes(): array
    {
        return $this->LetterType;
    }
}
