<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Matcher\MatcherInterface;

class MatchProcessor implements MatchProcessorInterface
{
    public function process(MatchResult $previous, MatcherInterface $matcher, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $matcher,
            result: $matcher->match($previous, $context),
        );
    }
}
