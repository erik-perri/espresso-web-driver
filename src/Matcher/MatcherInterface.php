<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;

interface MatcherInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult;

    public function __toString(): string;
}
