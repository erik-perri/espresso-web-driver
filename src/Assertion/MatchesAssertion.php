<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class MatchesAssertion implements AssertionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function assert(MatchResult $result, EspressoContext $context): bool
    {
        $element = $result->single();

        $matches = $this->matcher->match($element, $context);

        $filteredToContainer = array_filter(
            $matches,
            fn (WebDriverElement $match) => $match->getID() === $element->getID(),
        );

        return count($filteredToContainer) > 0;
    }

    public function __toString(): string
    {
        return sprintf('matches(%1$s)', $this->matcher);
    }
}
