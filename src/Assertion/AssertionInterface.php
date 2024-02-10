<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\WebDriverElement;

interface AssertionInterface
{
    public function assert(WebDriverElement $root, EspressoOptions $options): bool;
}
