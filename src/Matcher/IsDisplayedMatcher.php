<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use EspressoWebDriver\Utilities\ElementDisplayChecker;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchHiddenElements($container->single(), $context)
                : $this->matchDisplayedElements($container->single(), $context),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchDisplayedElements(WebDriverElement $container, MatchContext $context): array
    {
        $elements = [];

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
     */
    private function matchHiddenElements(WebDriverElement $container, MatchContext $context): array
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
        return 'displayed';
    }
}
