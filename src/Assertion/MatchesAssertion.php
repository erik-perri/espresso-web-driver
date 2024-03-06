<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Matcher\MatcherInterface;

final readonly class MatchesAssertion implements AssertionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function assert(MatchResult $container, EspressoContext $context): bool
    {
        $matches = $context->options->matchProcessor->process($container, $this->matcher, new EspressoContext(
            driver: $context->driver,
            options: $context->options,
        ));

        $containerElement = $container->single();

        foreach ($matches->all() as $match) {
            if ($containerElement->getID() === $match->getID()) {
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
