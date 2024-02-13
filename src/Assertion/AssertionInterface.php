<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatchResult;

interface AssertionInterface
{
    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function assert(MatchResult $container, EspressoContext $context): bool;

    public function __toString(): string;
}
