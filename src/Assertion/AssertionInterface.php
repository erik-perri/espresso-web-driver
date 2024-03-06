<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;

interface AssertionInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function assert(MatchResult $container, EspressoContext $context): bool;

    public function __toString(): string;
}
