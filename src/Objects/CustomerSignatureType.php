<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Карточка электронных подписей клиента (Settings/Data/CryptoParameters/CustomerSignature)
 */
class CustomerSignatureType
{
    /**
     * @var GroupSignaturesType[]
     */
    protected array $GroupSignatures = [];

    /**
     * @return GroupSignaturesType[]
     */
    public function getGroupSignatures(): array
    {
        return $this->GroupSignatures;
    }
}
