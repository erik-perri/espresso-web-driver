<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Utilities;

use EspressoWebDriver\Processor\UrlProcessorInterface;

class StaticUrlProcessor implements UrlProcessorInterface
{
    public function process(string $url): string
    {
        if (!str_starts_with($url, '/') || str_starts_with($url, '//')) {
            return $url;
        }

        $startUrl = rtrim($_ENV['SELENIUM_START_URL'], '/');

        return sprintf('%1$s%2$s', $startUrl, $url);
    }
}
