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

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::removeFailureOutput(static::getFailureOutputPath());
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::quitDriver();
    }

    protected function espresso(EspressoOptions $options = new EspressoOptions): EspressoCore
    {
        return usingDriver(
            $this->driver(),
            $options,
        );
    }

    abstract protected static function getFailureOutputPath(): string;

    abstract protected function getSeleniumUrl(): string;

    protected function driver(): WebDriver
    {
        if (self::$driver !== null) {
            return self::$driver;
        }

        $lockFileName = implode(
            DIRECTORY_SEPARATOR,
            [sys_get_temp_dir(), sprintf('phpunit-test-running-%1$s.lock', hash('sha256', __FILE__))],
        );

        touch($lockFileName);
        $lockFile = fopen($lockFileName, 'r');
        if (!$lockFile) {
            throw new \RuntimeException(sprintf(
                'Could not create lock file "%1$s" to prevent multiple test instances from running at once.',
                $lockFileName,
            ));
        }

        if (!flock($lockFile, LOCK_EX | LOCK_NB)) {
            flock($lockFile, LOCK_SH);
        }

        return self::$driver = RemoteWebDriver::create(
            $this->getSeleniumUrl(),
            $this->getSeleniumOptions()->toCapabilities(),
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

    private static function removeFailureOutput(string $filePrefix): void
    {
        $paths = [
            sprintf('%1$s/*.png', rtrim($filePrefix, '/')),
            sprintf('%1$s/*-source.txt', rtrim($filePrefix, '/')),
            sprintf('%1$s/*-console.txt', rtrim($filePrefix, '/')),
        ];

        /** @var string[] $files */
        $files = array_merge(...array_map(fn (string $path) => glob($path) ?: [], $paths));

        foreach ($files as $file) {
            unlink($file);
        }
    }

    protected function saveFailureOutput(WebDriver $driver, string $filePrefix): void
    {
        $driver->takeScreenshot($filePrefix.'.png');

        file_put_contents($filePrefix.'-source.txt', $driver->getPageSource());

        $console = $driver->manage()->getLog('browser');
        if (count($console)) {
            file_put_contents($filePrefix.'-console.txt', json_encode($console, JSON_PRETTY_PRINT));
        }
    }
}
