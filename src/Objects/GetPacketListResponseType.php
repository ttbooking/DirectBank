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

    public function getTimeStampLastPacket(): ?string
    {
        return $this->TimeStampLastPacket;
    }

    /**
     * @return string[]
     */
    public function getPacketID(): array
    {
        return $this->PacketID;
    }
}