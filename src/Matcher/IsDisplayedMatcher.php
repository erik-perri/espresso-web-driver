<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $context->isNegated
            ? $this->matchElementsWithoutVisibility($container->single())
            : $this->matchElementsWithVisibility($container->single());
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithVisibility(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->isDisplayed()) {
            $elements[] = $container;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if ($element->isDisplayed()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutVisibility(WebDriverElement $container): array
    {
        $elements = [];

        if (!$container->isDisplayed()) {
            $elements[] = $container;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $container->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if (!$element->isDisplayed()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return 'isDisplayed';
    }
}
