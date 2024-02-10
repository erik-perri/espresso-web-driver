<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature;

use EspressoWebDriver\Tests\Helpers\FeatureTestState;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\TestCase;
use Throwable;

class BaseFeatureTestCase extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        FeatureTestState::instance()->cleanup();
    }

    protected function onNotSuccessfulTest(Throwable $t): never
    {
        FeatureTestState::instance()->handleFailure(static::class, $this->nameWithDataSet());

        parent::onNotSuccessfulTest($t);
    }

    protected function driver(): WebDriver
    {
        return FeatureTestState::instance()->driver();
    }

    protected function mockStaticUrl(string $url): string
    {
        $startUrl = rtrim($_ENV['SELENIUM_START_URL'], '/');

        return sprintf('%1$s/%2$s', $startUrl, $url);
    }
}
