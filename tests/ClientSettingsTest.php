<?php
declare(strict_types=1);

namespace TTBooking\DirectBank;

use PHPUnit\Framework\TestCase;
use TTBooking\DirectBank\Exceptions\InvalidSettingsException;

final class ClientSettingsTest extends TestCase
{
    protected array $settings = [
        'url' => 'https://bank.example.ru/API/v1/directbank/',
        'customerId' => '40702810000000000000',
        'login' => 'user',
        'password' => 'secret',
    ];

    public function testValidSettings()
    {
        $this->assertInstanceOf(Client::class, new Client($this->settings));
        $this->assertInstanceOf(Client::class, new Client(['sessionId' => 'SID', 'verify' => false] + $this->settings));
        $this->assertInstanceOf(Client::class, new Client(['verify' => '/path/to/ca.pem'] + $this->settings));
    }

    public function requiredSettingsProvider(): array
    {
        $cases = [];

        foreach (['url', 'customerId', 'login', 'password', 'apiVersion'] as $key) {
            $cases["$key missing"] = [$key, null];
            $cases["$key empty"] = [$key, ''];
            $cases["$key not string"] = [$key, 123];
        }

        return $cases;
    }

    /**
     * @dataProvider requiredSettingsProvider
     */
    public function testRequiredSettings(string $key, mixed $value)
    {
        $this->expectException(InvalidSettingsException::class);
        $this->expectExceptionMessage("\"$key\"");

        new Client([$key => $value] + $this->settings);
    }

    public function testInvalidSessionId()
    {
        $this->expectException(InvalidSettingsException::class);
        $this->expectExceptionMessage('"sessionId"');

        new Client(['sessionId' => 123] + $this->settings);
    }

    public function optionalStringSettingsProvider(): array
    {
        $cases = [];

        foreach (['sessionId', 'availableApiVersion', 'userAgent'] as $key) {
            $cases["$key empty"] = [$key, ''];
            $cases["$key not string"] = [$key, 123];
        }

        return $cases;
    }

    /**
     * @dataProvider optionalStringSettingsProvider
     */
    public function testOptionalStringSettings(string $key, mixed $value)
    {
        $this->assertInstanceOf(Client::class, new Client([$key => null] + $this->settings));

        $this->expectException(InvalidSettingsException::class);
        $this->expectExceptionMessage("\"$key\"");

        new Client([$key => $value] + $this->settings);
    }

    public function testInvalidVerify()
    {
        $this->expectException(InvalidSettingsException::class);
        $this->expectExceptionMessage('"verify"');

        new Client(['verify' => 1] + $this->settings);
    }

    public function testInvalidHandler()
    {
        $this->expectException(InvalidSettingsException::class);
        $this->expectExceptionMessage('"handler"');

        new Client(['handler' => 'not a handler'] + $this->settings);
    }
}
