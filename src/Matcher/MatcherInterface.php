<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\WebDriverElement;

interface MatcherInterface
{
    /**
     * @return WebDriverElement[]
     */
    public function match(WebDriverElement $root, EspressoOptions $options): array;

    public function __toString(): string;
}
