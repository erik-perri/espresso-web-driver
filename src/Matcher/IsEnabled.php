<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsEnabled implements MatcherInterface
{
    public function match(WebDriverElement $root): array
    {
        $elements = [];

        if ($root->isEnabled()) {
            $elements[] = $root;
        }

        $possibleElements = $root->findElements(
            WebDriverBy::cssSelector('button, fieldset, optgroup, option, select, textarea, input'),
        );

        foreach ($possibleElements as $element) {
            if ($element->isEnabled()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }
}
