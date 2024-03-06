<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverElement;

interface NegativeMatcherInterface
{
    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function matchNegative(MatchResult $container, EspressoContext $context): array;
}
