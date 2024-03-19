<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\EspressoWebDriverException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Processor\MatchResult;
use EspressoWebDriver\Utilities\ElementDisplayChecker;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedInViewportMatcher implements MatcherInterface, NegativeMatcherInterface
{
    /**
     * @throws AmbiguousElementException|EspressoWebDriverException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): array
    {
        // TODO Move to a part of context for reuse of values? How would we know when to update?
        $checker = new ElementDisplayChecker($context->driver);

        return array_filter(
            $this->findPotentialElements($container),
            fn (WebDriverElement $element) => $checker->isDisplayed($element),
        );
    }

    /**
     * @throws AmbiguousElementException|EspressoWebDriverException|NoMatchingElementException
     */
    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        // TODO Move to a part of context for reuse of values? How would we know when to update?
        $checker = new ElementDisplayChecker($context->driver);

        return array_filter(
            $this->findPotentialElements($container),
            fn (WebDriverElement $element) => !$checker->isDisplayed($element),
        );
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function findPotentialElements(MatchResult $container): array
    {
        $element = $container->single();

        return [
            $element,
            // TODO This is probably a bad idea on dom heavy pages
            ...$element->findElements(WebDriverBy::cssSelector('*')),
        ];
    }

    public function __toString(): string
    {
        return 'isDisplayedInViewport';
    }
}
