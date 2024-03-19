<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;

final readonly class MatchesAssertion implements AssertionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function assert(
        MatcherInterface $target,
        ?MatcherInterface $container,
        EspressoContext $context,
    ): void {
        $targetResult = $context->options->matchProcessor->process(
            target: $target,
            container: $container,
            context: $context,
            expectedCount: ExpectedMatchCount::One,
        );

        $targetElement = $targetResult->single();

        $matches = $context->options->matchProcessor->process(
            target: $this->matcher,
            container: $targetResult,
            context: $context,
            expectedCount: ExpectedMatchCount::OneOrMore,
        );

        $found = array_filter($matches->all(), fn ($match) => $targetElement->getID() === $match->getID());

        if (empty($found)) {
            throw new AssertionFailedException($this);
        }
    }

    public function __toString(): string
    {
        return sprintf('matches(%1$s)', $this->matcher);
    }
}
