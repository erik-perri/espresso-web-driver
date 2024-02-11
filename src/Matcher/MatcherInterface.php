<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverElement;

interface MatcherInterface
{
    /**
     * @return WebDriverElement[]
     */
    public function match(WebDriverElement $container, EspressoContext $context): array;

    public function __toString(): string;
}
