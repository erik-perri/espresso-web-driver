<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;

interface MatcherInterface
{
    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function match(MatchResult $container, MatchContext $context): MatchResult;

    public function __toString(): string;
}
