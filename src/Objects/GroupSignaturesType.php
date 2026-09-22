<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Группа электронных подписей клиента (CustomerSignature/GroupSignatures)
 */
class GroupSignaturesType
{
    /**
     * @xmlAttribute
     */
    protected int $numberGroup;

    /**
     * Сертификаты электронных подписей сотрудников клиента, base64
     *
     * @var string[]
     */
    protected array $Certificate = [];

    public function getNumberGroup(): int
    {
        return $this->numberGroup;
    }

    /**
     * @return string[]
     */
    public function getCertificates(): array
    {
        return $this->Certificate;
    }
}
