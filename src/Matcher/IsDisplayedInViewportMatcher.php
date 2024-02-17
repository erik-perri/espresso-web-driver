<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\EspressoWebDriverException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use EspressoWebDriver\Utilities\ElementDisplayChecker;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedInViewportMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    /**
     * @throws AmbiguousElementException|EspressoWebDriverException|NoMatchingElementException
     */
    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchOffScreenElements($container->single(), $context)
                : $this->matchOnScreenElements($container->single(), $context),
        );
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws EspressoWebDriverException
     */
    private function matchOnScreenElements(WebDriverElement $container, MatchContext $context): array
    {
        $elements = [];

        // TODO Move to a part of context for reuse of values? How would we know when to update?
        $checker = new ElementDisplayChecker($context->driver);

        if ($checker->isDisplayed($container)) {
            $elements[] = $container;
        }

        $potentiallyVisibleElements = $container->findElements(
            WebDriverBy::cssSelector('*:not([style*="display: none"]):not([style*="visibility: hidden"])'),
        );

        foreach ($potentiallyVisibleElements as $element) {
            if ($checker->isDisplayed($element)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws EspressoWebDriverException
     */
    private function matchOffScreenElements(WebDriverElement $container, MatchContext $context): array
    {
        $elements = [];

        $checker = new ElementDisplayChecker($context->driver);

        if (!$checker->isDisplayed($container)) {
            $elements[] = $container;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentiallyHiddenElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentiallyHiddenElements as $element) {
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
