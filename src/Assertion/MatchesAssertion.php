<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
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
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function assert(MatchResult $container, EspressoContext $context): bool
    {
        $matches = $context->options->matchProcessor->process($container, $this->matcher, new EspressoContext(
            driver: $context->driver,
            options: $context->options,
        ));

        $filteredToResult = array_filter(
            $matches->all(),
            fn (WebDriverElement $match) => !empty(array_filter(
                $container->all(),
                fn (WebDriverElement $element) => $element->getID() === $match->getID(),
            )),
        );

        return count($filteredToResult) > 0;
    }

    public function __toString(): string
    {
        return sprintf('matches(%1$s)', $this->matcher);
    }
}
