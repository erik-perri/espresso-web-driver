<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverElement;

interface MatcherInterface
{
    /**
     * @return WebDriverElement[]
     */
    public function match(WebDriverElement $root): array;
}
