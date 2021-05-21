<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


class SuccessResultType
{
    protected ?LogonResponseType $LogonResponse = null;

    protected ?SendPacketResponseType $SendPacketResponse = null;

    protected ?GetPacketListResponseType $GetPacketListResponse = null;

    protected ?Packet $GetPacketResponse = null;

    /**
     * @return \TTBooking\DirectBank\Objects\LogonResponseType|null
     */
    public function getLogonResponse(): ?LogonResponseType
    {
        return $this->LogonResponse;
    }

    public function getSendPacketResponse(): ?SendPacketResponseType
    {
        return $this->SendPacketResponse;
    }

    public function getGetPacketListResponse(): ?GetPacketListResponseType
    {
        return $this->GetPacketListResponse;
    }

    public function getGetPacketResponse(): ?Packet
    {
        return $this->GetPacketResponse;
    }
}