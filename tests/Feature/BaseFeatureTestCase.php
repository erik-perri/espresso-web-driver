<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use Throwable;

class BaseFeatureTestCase extends TestCase
{
    private static bool $firstRun = true;

    private static ?WebDriver $driver = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (static::$firstRun) {
            static::$firstRun = false;
            static::clearOutput();
        }

        self::$driver = self::createWebDriver();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::quitDriver();
    }

    protected function onNotSuccessfulTest(Throwable $t): never
    {
        $this->saveCurrentTestOutput();

        parent::onNotSuccessfulTest($t);
    }

    protected function driver(): WebDriver
    {
        if (self::$driver === null) {
            throw new RuntimeException('WebDriver is not initialized');
        }

        return self::$driver;
    }

    protected function mockStaticUrl(string $url): string
    {
        $startUrl = rtrim($_ENV['SELENIUM_START_URL'], '/');

        return sprintf('%1$s/%2$s', $startUrl, $url);
    }

    private static function createWebDriver(): WebDriver
    {
        $serverUrl = sprintf(
            '%1$s://%2$s:%3$d',
            $_ENV['SELENIUM_PROTOCOL'] ?? 'http',
            $_ENV['SELENIUM_HOST'] ?? 'localhost',
            $_ENV['SELENIUM_PORT'] ?? 4444,
        );

        $options = (new ChromeOptions)->addArguments(['--start-maximized']);

        return RemoteWebDriver::create(
            $serverUrl,
            $options->toCapabilities()
        );
    }

    private static function outputDirectory(): string
    {
        return __DIR__.'/output';
    }

    private static function quitDriver(): void
    {
        self::$driver?->quit();
        self::$driver = null;
    }

    private static function clearOutput(): void
    {
        $outputDirectory = static::outputDirectory();

        foreach (glob($outputDirectory.'/*') as $file) {
            unlink($file);
        }
    }

    private function saveCurrentTestOutput(): void
    {
        $shortClassName = (new ReflectionClass($this))->getShortName();
        $filePath = $this->outputDirectory();
        $filePrefix = sprintf('%1$s/%2$s-%3$s', $filePath, $shortClassName, $this->nameWithDataSet());

        self::$driver->takeScreenshot($filePrefix.'.png');
        file_put_contents($filePrefix.'-source.txt', self::$driver->getPageSource());
    }
}
