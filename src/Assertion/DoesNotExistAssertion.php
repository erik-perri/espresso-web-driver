<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatchResult;

final readonly class DoesNotExistAssertion implements AssertionInterface
{
    public function assert(MatchResult $container, EspressoContext $context): bool
    {
        return $container->count() === 0;
    }

    public function __toString(): string
    {
        return 'doesNotExist';
    }
}
