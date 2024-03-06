<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;

interface MatchProcessorInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function process(MatchResult $previous, MatcherInterface $matcher, EspressoContext $context): MatchResult;
}
