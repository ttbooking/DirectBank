<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Внутренний банковский документ в выписке (PayDoc/InnerDoc)
 */
class InnerDocType extends OtherPaymentDataType
{
    //Вид внутреннего банковского документа
    protected ?string $InnerDocKind = null;

    public function getInnerDocKind(): ?string
    {
        return $this->InnerDocKind;
    }
}
