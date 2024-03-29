<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Matcher\MatcherInterface;

interface MatchProcessorInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoRootElementException
     */
    public function process(
        MatcherInterface $target,
        MatcherInterface|MatchResult|null $container,
        EspressoContext $context,
        ExpectedMatchCount $expectedCount,
    ): MatchResult;
}
