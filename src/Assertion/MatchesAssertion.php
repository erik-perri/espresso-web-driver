<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchProcessorOptions;

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
    ): bool {
        $targetResult = $context->options->matchProcessor->process(
            target: $target,
            container: $container,
            context: $context,
            options: new MatchProcessorOptions(
                expectedCount: ExpectedMatchCount::Single,
            ),
        );

        $targetElement = $targetResult->single();

        $matches = $context->options->matchProcessor->process(
            target: $this->matcher,
            container: $targetResult,
            context: $context,
        );

        foreach ($matches->all() as $match) {
            if ($targetElement->getID() === $match->getID()) {
                return true;
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return sprintf('matches(%1$s)', $this->matcher);
    }
}
