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
        return $this->waitForMatch($context, fn () => $this->matchElements($container->single(), $context));
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElements(WebDriverElement $container, MatchContext $context): array
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

    public function __toString(): string
    {
        return 'displayed';
    }
}
