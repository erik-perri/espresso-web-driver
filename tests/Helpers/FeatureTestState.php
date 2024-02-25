<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Helpers;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class FeatureTestState
{
    private static self $instance;

    private ?WebDriver $driver = null;

    private function __construct(private readonly string $outputPath = __DIR__.'/../output')
    {
        $this->clearOutput();
    }

    public function __destruct()
    {
        // This should normally happen automatically via PHPUnit's `tearDownAfterClass`, but if a test calls exit
        // instead on completing that will not run. That sometimes leaves selenium unable to be interacted with until a
        // restart. This ensures we always quit, even if the tests do not complete normally.
        $this->quit();
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function driver(): WebDriver
    {
        if ($this->driver !== null) {
            return $this->driver;
        }

        $serverUrl = $_ENV['SELENIUM_DRIVER_URL'] ?? 'http://localhost:4444';

        $options = (new ChromeOptions)->addArguments(['--start-maximized']);

        return $this->driver = RemoteWebDriver::create(
            $serverUrl,
            $options->toCapabilities(),
        );
    }

    /**
     * @param  class-string<TestCase>  $testClass
     */
    public function handleFailure(string $testClass, string $testName): void
    {
        if ($this->driver === null) {
            return;
        }

        $shortClassName = (new ReflectionClass($testClass))->getShortName();
        $filePrefix = sprintf('%1$s/%2$s-%3$s', $this->outputPath, $shortClassName, $testName);

        $this->driver->takeScreenshot($filePrefix.'.png');

        file_put_contents($filePrefix.'-source.txt', $this->driver->getPageSource());
    }

    public function quit(): void
    {
        $this->driver?->quit();
        $this->driver = null;
    }

    private function clearOutput(): void
    {
        foreach (glob(sprintf('%1$s/*', $this->outputPath)) as $file) {
            unlink($file);
        }
    }
}
