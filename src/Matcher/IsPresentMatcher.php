<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;

final readonly class IsPresentMatcher implements MatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: [...$container->all()],
            isExpectingEmpty: $context->isNegated,
        );
    }

    public function __toString(): string
    {
        return 'isPresent';
    }
}
