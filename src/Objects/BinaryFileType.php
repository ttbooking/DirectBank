<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


use Ramsey\Uuid\Uuid;

/**
 * Файл вложения письма (Letter/Data/Attachment/BinaryFile): base64 с описанием файла
 */
class BinaryFileType
{
    /**
     * @xmlNodeValue
     */
    protected string $value;

    /**
     * Уникальный идентификатор вложения
     *
     * @xmlAttribute
     */
    protected string $id;

    /**
     * Полное имя файла с расширением
     *
     * @xmlAttribute
     */
    protected string $name;

    /**
     * Расширение имени файла
     *
     * @xmlAttribute
     */
    protected string $extension;

    /**
     * Размер файла в байтах
     *
     * @xmlAttribute
     */
    protected int $size;

    /**
     * Контрольная сумма файла, CRC32
     *
     * @xmlAttribute
     */
    protected int $crc;

    /**
     * @xmlAttribute
     */
    protected string $creationDate;

    /**
     * Вложение из содержимого файла: размер и CRC32 считаются по содержимому.
     * Содержимое передаётся как есть: BOM для текстовых файлов добавляет вызывающий код.
     */
    public static function fromContents(string $contents, string $name, ?\DateTimeInterface $creationDate = null, ?string $id = null): static
    {
        $file = new static();
        $file->value = base64_encode($contents);
        $file->id = $id ?? (string) Uuid::uuid4();
        $file->name = $name;
        $file->extension = pathinfo($name, PATHINFO_EXTENSION);
        $file->size = strlen($contents);
        $file->crc = crc32($contents);
        $file->creationDate = ($creationDate ?? new \DateTimeImmutable())->format('Y-m-d\TH:i:s');

        return $file;
    }

    public static function fromFile(string $path, ?string $name = null, ?string $id = null): static
    {
        $contents = @file_get_contents($path);
        if ($contents === false) {
            throw new \InvalidArgumentException(sprintf('Unable to read file "%s".', $path));
        }

        $modified = (new \DateTimeImmutable())->setTimestamp(filemtime($path));

        return static::fromContents($contents, $name ?? basename($path), $modified, $id);
    }

    /**
     * Содержимое файла в base64
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Содержимое файла
     */
    public function getContents(): string
    {
        $contents = base64_decode($this->value, true);
        if ($contents === false) {
            throw new \UnexpectedValueException('Attachment data is not valid base64.');
        }

        return $contents;
    }

    /**
     * Совпадают ли размер и CRC32 содержимого с указанными в описании файла
     */
    public function isIntact(): bool
    {
        $contents = $this->getContents();

        return strlen($contents) === $this->size && crc32($contents) === $this->crc;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getCrc(): int
    {
        return $this->crc;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }
}
