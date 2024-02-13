<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatchContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
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
    public function assert(MatchResult $container, EspressoContext $context): bool
    {
        $matches = $this->matcher->match($container, new MatchContext(
            driver: $context->driver,
            isNegated: false,
            options: $context->options,
        ));

        $filteredToResult = array_filter(
            $matches->all(),
            fn (WebDriverElement $match) => !empty(array_filter(
                $container->all(),
                fn (WebDriverElement $element) => $element->getID() === $match->getID()
            )),
        );

        $hasMatch = count($filteredToResult) > 0;

        if ($matches->isNegated) {
            return !$hasMatch;
        }

        return $hasMatch;
    }

    public function __toString(): string
    {
        return sprintf('matches(%1$s)', $this->matcher);
    }
}
