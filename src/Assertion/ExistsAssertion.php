<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;

final readonly class ExistsAssertion implements AssertionInterface
{
    public function assert(MatchResult $container, EspressoContext $context): bool
    {
        $container->single();

        return true;
    }

    public function __toString(): string
    {
        return 'exists';
    }
}
