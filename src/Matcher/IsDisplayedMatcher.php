<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    public function match(WebDriverElement $root): array
    {
        $elements = [];

        if ($root->isDisplayed()) {
            $elements[] = $root;
        }

        $visibleElements = $root->findElements(
            WebDriverBy::cssSelector('*:not([style*="display: none"]):not([style*="visibility: hidden"])'),
        );

        foreach ($visibleElements as $element) {
            if ($element->isDisplayed()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }
}
