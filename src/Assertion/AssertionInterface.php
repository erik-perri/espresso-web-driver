<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverElement;

interface AssertionInterface
{
    public function assert(WebDriverElement $container, EspressoContext $context): bool;

    public function __toString(): string;
}
