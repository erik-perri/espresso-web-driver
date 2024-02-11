<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\NoMatchingElementException;

final readonly class ExistsAssertion implements AssertionInterface
{
    public function assert(MatchResult $result, EspressoContext $context): bool
    {
        // We catch the NoMatchingElementException, but let the AmbiguousElementMatcherException bubble up.
        try {
            $result->single();
        } catch (NoMatchingElementException) {
            return false;
        }

        return true;
    }

    public function __toString(): string
    {
        return 'exists';
    }
}
