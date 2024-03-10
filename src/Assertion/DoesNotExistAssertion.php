<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;

final readonly class DoesNotExistAssertion implements AssertionInterface
{
    public function assert(
        MatcherInterface $target,
        ?MatcherInterface $container,
        EspressoContext $context,
    ): bool {
        $targetResult = $context->options->matchProcessor->process($target, $container, $context);

        return $targetResult->count() === 0;
    }

    public function __toString(): string
    {
        return 'doesNotExist';
    }
}
