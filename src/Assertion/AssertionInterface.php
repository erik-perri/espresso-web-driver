<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Matcher\MatcherInterface;

interface AssertionInterface
{
    /**
     * @throws AmbiguousElementException|AssertionFailedException|NoMatchingElementException|NoRootElementException
     */
    public function assert(
        MatcherInterface $target,
        ?MatcherInterface $container,
        EspressoContext $context,
    ): void;

    public function __toString(): string;
}
