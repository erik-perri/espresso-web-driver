<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature;

class BaseFeatureTestCase extends EspressoTestCase
{
    protected static function getFailureOutputPath(): string
    {
        return __DIR__.'/../output';
    }

    protected function getSeleniumUrl(): string
    {
        return $_ENV['SELENIUM_DRIVER_URL'] ?? 'http://localhost:4444';
    }

    protected function mockStaticUrl(string $url): string
    {
        $startUrl = rtrim($_ENV['SELENIUM_START_URL'], '/');

        return sprintf('%1$s/%2$s', $startUrl, $url);
    }
}
