<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\NoMatchingElementException;

final readonly class NotAssertion implements AssertionInterface
{
    public function __construct(private AssertionInterface $assertion)
    {
        //
    }

    public function assert(MatchResult $result, EspressoContext $context): bool
    {
        try {
            return !$this->assertion->assert($result, $context);
        } catch (NoMatchingElementException) {
            return true;
        }
    }

    public function __toString(): string
    {
        return sprintf('not(%1$s)', $this->assertion);
    }
}
