<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature;

use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Utilities\DriverManager;
use EspressoWebDriver\Tests\Utilities\OutputManager;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use EspressoWebDriver\Tests\Utilities\StaticUrlProcessor;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\TestCase;
use Throwable;

use function EspressoWebDriver\usingDriver;

class BaseFeatureTestCase extends TestCase
{
    private ?WebDriver $driver = null;

    protected function driver(): WebDriver
    {
        return $this->driver = DriverManager::getInstance()->driver(
            $_ENV['SELENIUM_DRIVER_URL'] ?? 'http://localhost:4444',
            (new ChromeOptions)
                ->addArguments(['--start-maximized'])
                ->toCapabilities(),
        );
    }

    protected function espresso(?EspressoOptions $options = null): EspressoCore
    {
        $options ??= new EspressoOptions(
            assertionReporter: new PhpunitReporter,
            urlProcessor: new StaticUrlProcessor,
        );

        return usingDriver($this->driver(), $options);
    }

    protected function onNotSuccessfulTest(Throwable $t): never
    {
        if ($this->driver) {
            (new OutputManager)->save($this->driver, $this);
        }

        parent::onNotSuccessfulTest($t);
    }
}
