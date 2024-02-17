<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;

final readonly class IsPresentMatcher implements MatcherInterface
{
    /**
     * @throws AmbiguousElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        try {
            $container->single();
        } catch (NoMatchingElementException) {
            return new MatchResult(matcher: $this, result: [], isExpectingEmpty: $context->isNegated);
        }

        return $container;
    }

    public function __toString(): string
    {
        return 'isPresent';
    }
}
