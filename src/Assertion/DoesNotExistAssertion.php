<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;

final readonly class DoesNotExistAssertion implements AssertionInterface
{
    public function assert(
        MatcherInterface $target,
        ?MatcherInterface $container,
        EspressoContext $context,
    ): void {
        $targetResult = $context->options->matchProcessor->process(
            target: $target,
            container: $container,
            context: $context,
            expectedCount: ExpectedMatchCount::Zero,
        );

        if ($targetResult->count() !== 0) {
            throw new AssertionFailedException($this);
        }
    }

    public function __toString(): string
    {
        return 'doesNotExist';
    }
}
