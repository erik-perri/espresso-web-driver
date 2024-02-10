<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasFocusMatcher implements MatcherInterface
{
    public function match(WebDriverElement $root): array
    {
        $elements = [];

        if ($root->isSelected()) {
            $elements[] = $root;
        }

        $focusElements = $root->findElements(WebDriverBy::cssSelector('*:focus'));

        foreach ($focusElements as $element) {
            if ($element->isSelected()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }
}
