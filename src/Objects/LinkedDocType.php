<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Объект обсуждения в письме, например платёжное поручение (Letter/Data/LinkedDoc)
 */
class LinkedDocType
{
    /**
     * Идентификатор документа
     *
     * @xmlAttribute
     */
    protected string $id;

    /**
     * Вид электронного документа
     *
     * @xmlAttribute
     */
    protected string $dockind;

    public function __construct(string $id, string $dockind)
    {
        $this->id = $id;
        $this->dockind = $dockind;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDockind(): string
    {
        return $this->dockind;
    }
}
