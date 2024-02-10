<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\WebDriverElement;

interface AssertionInterface
{
    public function assert(WebDriverElement $container, EspressoOptions $options): bool;

    public function __toString(): string;
}
