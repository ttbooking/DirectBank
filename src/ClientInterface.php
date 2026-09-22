<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;

use TTBooking\DirectBank\Objects\GetPacketListResponseType;
use TTBooking\DirectBank\Objects\Packet;
use TTBooking\DirectBank\Objects\Settings;

interface ClientInterface
{
    public function createSession(): string;

    public function confirmOtp(string $sessionId, string $otp): string;

    public function sendPack(Packet $packet): string;

    public function getPackList(?\DateTimeInterface $dateTime = null): ?array;

    public function getPackListResponse(\DateTimeInterface|string|null $since = null): GetPacketListResponseType;

    public function getPack(string $uid) : Packet;

    public function getSettings(string $inn, string $bic, ?string $account = null): Settings;
}
