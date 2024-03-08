<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature;

use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Throwable;

use function EspressoWebDriver\usingDriver;

abstract class EspressoTestCase extends TestCase
{
    private static ?WebDriver $driver = null;

    public function __destruct()
    {
        // This should normally happen automatically via PHPUnit's `tearDownAfterClass`, but if a test calls exit
        // instead on completing that will not run. That sometimes leaves selenium unable to be interacted with until a
        // restart. This ensures we always quit, even if the tests do not complete normally.
        self::quitDriver();
    }

    protected function driver(): WebDriver
    {
        if (self::$driver !== null) {
            return self::$driver;
        }

        return self::$driver = RemoteWebDriver::create(
            $this->getSeleniumUrl(),
            $this->getSeleniumOptions()->toCapabilities(),
        );
    }

    protected function espresso(?EspressoOptions $options = null): EspressoCore
    {
        return usingDriver(
            $this->driver(),
            $options ?? $this->getEspressoOptions(),
        );
    }

    protected function getEspressoOptions(): EspressoOptions
    {
        return new EspressoOptions;
    }

    protected function getSeleniumOptions(): ChromeOptions
    {
        return (new ChromeOptions)->addArguments(['--start-maximized']);
    }

    abstract protected function getSeleniumUrl(): string;

    protected function onNotSuccessfulTest(Throwable $t): never
    {
        $outputPath = static::getFailureOutputPath();
        if ($outputPath) {
            $this->saveFailureOutput($outputPath);
        }

        parent::onNotSuccessfulTest($t);
    }

    private function saveFailureOutput(string $path): void
    {
        if (!self::$driver) {
            return;
        }

        $shortClassName = (new ReflectionClass(static::class))->getShortName();

        $filePrefix = sprintf(
            '%1$s/%2$s-%3$s',
            $path,
            $shortClassName,
            $this->nameWithDataSet(),
        );

        self::$driver->takeScreenshot($filePrefix.'.png');

        file_put_contents($filePrefix.'-source.txt', self::$driver->getPageSource());

        $console = self::$driver->manage()->getLog('browser');
        if (count($console)) {
            file_put_contents($filePrefix.'-console.txt', json_encode($console, JSON_PRETTY_PRINT));
        }
    }

    protected static function getFailureOutputPath(): ?string
    {
        return null;
    }

    protected static function quitDriver(): void
    {
        self::$driver?->quit();
        self::$driver = null;
    }

    private static function removeFailureOutput(string $path): void
    {
        $paths = [
            sprintf('%1$s/*.png', rtrim($path, '/')),
            sprintf('%1$s/*-source.txt', rtrim($path, '/')),
            sprintf('%1$s/*-console.txt', rtrim($path, '/')),
        ];

        /** @var string[] $files */
        $files = array_merge(...array_map(fn (string $search) => glob($search) ?: [], $paths));

        foreach ($files as $file) {
            unlink($file);
        }
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $outputPath = static::getFailureOutputPath();
        if ($outputPath) {
            self::removeFailureOutput($outputPath);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::quitDriver();
    }
}
