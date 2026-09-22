<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Цифровой след отправителя: IP- и MAC-адреса клиента (Packet/SenderFootprint), с версии 2.3.2
 */
class SenderFootprintType
{
    /**
     * IP-адреса, до 39 символов (IPv6)
     *
     * @var string[]
     */
    protected array $IP = [];

    /**
     * MAC-адреса, до 17 символов
     *
     * @var string[]
     */
    protected array $MAC = [];

    /**
     * @param string[] $ip
     * @param string[] $mac
     */
    public function __construct(array $ip = [], array $mac = [])
    {
        $this->IP = array_values($ip);
        $this->MAC = array_values($mac);
    }

    /**
     * @return string[]
     */
    public function getIP(): array
    {
        return $this->IP;
    }

    public function addIP(string $ip): SenderFootprintType
    {
        $this->IP[] = $ip;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getMAC(): array
    {
        return $this->MAC;
    }

    public function addMAC(string $mac): SenderFootprintType
    {
        $this->MAC[] = $mac;
        return $this;
    }
}
