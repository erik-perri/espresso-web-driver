<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature;

use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Processor\RetryingMatchProcessor;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
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

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::quitDriver();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->removeFailureOutput($this->getFailureFilePrefix());
    }

    protected function espresso(): EspressoCore
    {
        return usingDriver(
            $this->driver(),
            $this->getEspressoOptions(),
        );
    }

    abstract protected function getFailureOutputPath(): string;

    abstract protected function getSeleniumUrl(): string;

    protected function driver(): WebDriver
    {
        if (self::$driver !== null) {
            return self::$driver;
        }

        $lockFileName = implode(
            DIRECTORY_SEPARATOR,
            [sys_get_temp_dir(), sprintf('phpunit-test-running-%1$s.lock', hash('sha256', self::class))],
        );

        touch($lockFileName);
        $lockFile = fopen($lockFileName, 'r');

        if (!flock($lockFile, LOCK_EX | LOCK_NB)) {
            flock($lockFile, LOCK_SH);
        }

        return self::$driver = RemoteWebDriver::create(
            $this->getSeleniumUrl(),
            $this->getSeleniumOptions()->toCapabilities(),
        );
    }

    protected function getEspressoOptions(): EspressoOptions
    {
        return new EspressoOptions(
            matchProcessor: new RetryingMatchProcessor(
                waitTimeoutInSeconds: 3,
                waitIntervalInMilliseconds: 250,
            ),
            assertionReporter: new PhpunitReporter,
        );
    }

    protected function getFailureFilePrefix(): string
    {
        $shortClassName = (new ReflectionClass(static::class))->getShortName();

        return sprintf(
            '%1$s/%2$s-%3$s',
            $this->getFailureOutputPath(),
            $shortClassName,
            $this->nameWithDataSet(),
        );
    }

    protected function getSeleniumOptions(): ChromeOptions
    {
        return (new ChromeOptions)->addArguments(['--start-maximized']);
    }

    protected function onNotSuccessfulTest(Throwable $t): never
    {
        if (self::$driver !== null) {
            $this->saveFailureOutput(self::$driver, $this->getFailureFilePrefix());
        }

        parent::onNotSuccessfulTest($t);
    }

    protected static function quitDriver(): void
    {
        self::$driver?->quit();
        self::$driver = null;
    }

    private function removeFailureOutput(string $filePrefix): void
    {
        foreach (glob(sprintf('%1$s/*', $filePrefix)) as $file) {
            unlink($file);
        }
    }

    protected function saveFailureOutput(WebDriver $driver, string $filePrefix): void
    {
        $driver->takeScreenshot($filePrefix.'.png');

        file_put_contents($filePrefix.'-source.txt', $driver->getPageSource());
    }
}
