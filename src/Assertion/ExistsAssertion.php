<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchProcessorOptions;

final readonly class ExistsAssertion implements AssertionInterface
{
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

        $targetResult->single();

        return true;
    }

    public function __toString(): string
    {
        return 'exists';
    }
}
