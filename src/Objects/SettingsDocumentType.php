<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Вид электронного документа, которым возможен обмен с банком (Settings/Data/Document)
 */
class SettingsDocumentType
{
    /**
     * @xmlAttribute
     */
    protected string $docKind;

    //Применение электронной подписи для данного вида документа
    protected ?DocumentSignedType $Signed = null;

    public function getDocKind(): string
    {
        return $this->docKind;
    }

    public function getSigned(): ?DocumentSignedType
    {
        return $this->Signed;
    }
}
