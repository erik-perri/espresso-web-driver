<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\NoMatchingElementException;

final readonly class ExistsMatcher implements MatcherInterface
{
    public function match(MatchResult $container, MatchContext $context): MatchResult
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
        return 'exists';
    }
}
