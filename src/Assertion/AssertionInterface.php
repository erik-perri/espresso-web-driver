<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use Facebook\WebDriver\WebDriverElement;

interface AssertionInterface
{
    public function assert(WebDriverElement $root): bool;
}
