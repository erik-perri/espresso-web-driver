<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\EspressoWebDriverException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Utilities\ElementDisplayChecker;
use Facebook\WebDriver\WebDriverBy;

final readonly class IsDisplayedInViewportMatcher implements MatcherInterface, NegativeMatcherInterface
{
    /**
     * @throws AmbiguousElementException|EspressoWebDriverException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): array
    {
        $elements = [];

        // TODO Move to a part of context for reuse of values? How would we know when to update?
        $checker = new ElementDisplayChecker($context->driver);

        foreach ($container->all() as $containerElement) {
            if ($checker->isDisplayed($containerElement)) {
                $elements[] = $containerElement;
            }
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if ($checker->isDisplayed($element)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @throws AmbiguousElementException|EspressoWebDriverException|NoMatchingElementException
     */
    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        $elements = [];

        // TODO Move to a part of context for reuse of values? How would we know when to update?
        $checker = new ElementDisplayChecker($context->driver);

        foreach ($container->all() as $containerElement) {
            if (!$checker->isDisplayed($containerElement)) {
                $elements[] = $containerElement;
            }
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if (!$checker->isDisplayed($element)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return 'isDisplayedInViewport';
    }
}
