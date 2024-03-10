<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;

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
        $targetResult = $context->options->matchProcessor->process($target, $container, $context);

        $targetElement = $targetResult->single();

        $matches = $context->options->matchProcessor->process($this->matcher, $targetResult, $context);

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
