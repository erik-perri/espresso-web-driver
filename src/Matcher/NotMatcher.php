<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\NoMatchingElementException;

final readonly class NotMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        try {
            return $this->matcher->match($container, new MatchContext(
                driver: $context->driver,
                isNegated: true,
                options: $context->options,
            ));
        } catch (NoMatchingElementException) {
            return new MatchResult(
                matcher: $this,
                result: [],
                isNegated: $context->isNegated,
            );
        }
    }

    public function __toString(): string
    {
        return sprintf('not(%1$s)', $this->matcher);
    }
}
