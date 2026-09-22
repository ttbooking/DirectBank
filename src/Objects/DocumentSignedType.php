<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Применение электронной подписи для вида документа (Settings/Data/Document/Signed)
 */
class DocumentSignedType
{
    //Правило, задающее наличие электронных подписей для данного вида документа
    protected string $RuleSignatures;

    public function getRuleSignatures(): string
    {
        return $this->RuleSignatures;
    }
}
