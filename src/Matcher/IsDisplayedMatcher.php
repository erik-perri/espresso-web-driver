<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
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
