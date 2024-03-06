<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class IsDisplayedMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        $containerElement = $container->single();
        $elements = [];

        if ($containerElement->isDisplayed()) {
            $elements[] = $containerElement;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $containerElement->findElements(WebDriverBy::cssSelector('*'));

        foreach ($potentialElements as $element) {
            if ($element->isDisplayed()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        $containerElement = $container->single();
        $elements = [];

        if (!$containerElement->isDisplayed()) {
            $elements[] = $containerElement;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentialElements = $containerElement->findElements(WebDriverBy::cssSelector('*'));

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
