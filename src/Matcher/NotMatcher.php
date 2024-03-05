<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;

final readonly class NotMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $this->matcher->match($container, new EspressoContext(
            driver: $context->driver,
            options: $context->options,
            isNegated: true,
        ));
    }

    public function __toString(): string
    {
        return sprintf('not(%1$s)', $this->matcher);
    }
}
