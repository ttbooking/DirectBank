<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Содержимое электронного документа (Document/Data): base64 с необязательными атрибутами файла
 */
class DataType
{
    /**
     * @xmlNodeValue
     */
    protected string $value;

    /**
     * @xmlAttribute
     */
    protected ?string $fileName = null;

    /**
     * @xmlAttribute
     */
    protected ?string $contentType = null;

    public function __construct(string $value = '', ?string $fileName = null, ?string $contentType = null)
    {
        $this->value = $value;
        $this->fileName = $fileName;
        $this->contentType = $contentType;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }
}
