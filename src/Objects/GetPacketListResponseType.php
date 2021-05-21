<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class GetPacketListResponseType
{
    /**
     * @xmlAttribute
     */
    protected string $TimeStampLastPacket;

    /**
     * @var string[]
     */
    protected array $PacketID = [];

    /**
     * @return string[]
     */
    public function getPacketID(): array
    {
        return $this->PacketID;
    }
}