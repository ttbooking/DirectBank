<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class GetPacketListResponseType
{
    /**
     * @xmlAttribute
     */
    protected ?string $TimeStampLastPacket = null;

    /**
     * @var string[]
     */
    protected array $PacketID = [];

    /**
     * Отметка времени последнего контейнера в списке (xsd:dateTime, по часам сервера банка).
     * Передаётся в следующий запрос списка, чтобы получить только новые контейнеры.
     */
    public function getTimeStampLastPacket(): ?string
    {
        return $this->TimeStampLastPacket;
    }

    public function getTimeStampLastPacketDateTime(): ?\DateTimeImmutable
    {
        return $this->TimeStampLastPacket === null ? null : new \DateTimeImmutable($this->TimeStampLastPacket);
    }

    /**
     * @return string[]
     */
    public function getPacketID(): array
    {
        return $this->PacketID;
    }
}