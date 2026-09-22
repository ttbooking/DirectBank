<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Данные письма (Letter/Data)
 */
class LetterDataType
{
    /**
     * ID изначального письма
     *
     * @xmlAttribute
     */
    protected ?string $linkedID = null;

    /**
     * ID переписки
     *
     * @xmlAttribute
     */
    protected ?string $correspondenceID = null;

    //Номер документа
    protected string $DocNum;

    //Дата составления
    protected string $DocDate;

    //Код типа письма, из настроек обмена (Settings/Data/Letters)
    protected ?string $LetterTypeCode = null;

    //Тема письма, до 100 символов
    protected ?string $Theme = null;

    //Текст письма
    protected string $Text;

    /**
     * @var LetterAttachmentType[]
     */
    protected array $Attachment = [];

    //Объект обсуждения, например платёжное поручение
    protected ?LinkedDocType $LinkedDoc = null;

    public function getLinkedID(): ?string
    {
        return $this->linkedID;
    }

    public function setLinkedID(?string $linkedID): LetterDataType
    {
        $this->linkedID = $linkedID;
        return $this;
    }

    public function getCorrespondenceID(): ?string
    {
        return $this->correspondenceID;
    }

    public function setCorrespondenceID(?string $correspondenceID): LetterDataType
    {
        $this->correspondenceID = $correspondenceID;
        return $this;
    }

    public function getDocNum(): string
    {
        return $this->DocNum;
    }

    public function setDocNum(string $docNum): LetterDataType
    {
        $this->DocNum = $docNum;
        return $this;
    }

    public function getDocDate(): string
    {
        return $this->DocDate;
    }

    public function setDocDate(string $docDate): LetterDataType
    {
        $this->DocDate = $docDate;
        return $this;
    }

    public function getLetterTypeCode(): ?string
    {
        return $this->LetterTypeCode;
    }

    public function setLetterTypeCode(?string $letterTypeCode): LetterDataType
    {
        $this->LetterTypeCode = $letterTypeCode;
        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->Theme;
    }

    public function setTheme(?string $theme): LetterDataType
    {
        $this->Theme = $theme;
        return $this;
    }

    public function getText(): string
    {
        return $this->Text;
    }

    public function setText(string $text): LetterDataType
    {
        $this->Text = $text;
        return $this;
    }

    /**
     * @return LetterAttachmentType[]
     */
    public function getAttachments(): array
    {
        return $this->Attachment;
    }

    public function addAttachment(LetterAttachmentType $attachment): LetterDataType
    {
        $this->Attachment[] = $attachment;
        return $this;
    }

    public function getLinkedDoc(): ?LinkedDocType
    {
        return $this->LinkedDoc;
    }

    public function setLinkedDoc(?LinkedDocType $linkedDoc): LetterDataType
    {
        $this->LinkedDoc = $linkedDoc;
        return $this;
    }
}
