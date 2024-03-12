<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Utilities;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;

final class DriverManager
{
    private static ?DriverManager $instance = null;

    /**
     * @var array<string, WebDriver>
     */
    private array $drivers = [];

    public static function getInstance(): DriverManager
    {
        if (self::$instance === null) {
            self::$instance = new DriverManager;
        }

        return self::$instance;
    }

    public function __destruct()
    {
        foreach ($this->drivers as $driver) {
            $driver->quit();
        }
    }

    public function driver(string $serverUrl, DesiredCapabilities $desiredCapabilities): WebDriver
    {
        $hash = $this->hashDriver($serverUrl, $desiredCapabilities);

        if (!isset($this->drivers[$hash])) {
            $this->drivers[$hash] = $this->createDriver($serverUrl, $desiredCapabilities);
        }

        return $this->drivers[$hash];
    }

    private function createDriver(string $serverUrl, DesiredCapabilities $desiredCapabilities): WebDriver
    {
        return RemoteWebDriver::create($serverUrl, $desiredCapabilities);
    }

    private function hashDriver(string $serverUrl, DesiredCapabilities $desiredCapabilities): string
    {
        return hash('sha256', $serverUrl.json_encode($desiredCapabilities->toArray()));
    }
}
