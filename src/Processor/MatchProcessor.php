<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;

class MatchProcessor implements MatchProcessorInterface
{
    public function process(MatchResult $previous, MatcherInterface $matcher, EspressoContext $context): MatchResult
    {
        return $matcher->match($previous, $context);
    }
}
