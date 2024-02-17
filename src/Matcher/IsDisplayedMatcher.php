<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchElementsWithoutVisibility($container->single())
                : $this->matchElementsWithVisibility($container->single()),
        );
    }

    /**
     * @return array<string, WebDriverElement>
     */
    private function matchElementsWithVisibility(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->isDisplayed()) {
            $elements[$container->getID()] = $container;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if ($element->isDisplayed()) {
                $elements[$element->getID()] = $element;
            }
        }

        return $elements;
    }

    /**
     * @return array<string, WebDriverElement>
     */
    private function matchElementsWithoutVisibility(WebDriverElement $container): array
    {
        $elements = [];

        if (!$container->isDisplayed()) {
            $elements[$container->getID()] = $container;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if (!$element->isDisplayed()) {
                $elements[$element->getID()] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return 'isDisplayed';
    }
}
