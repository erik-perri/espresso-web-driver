<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsCheckedMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        $potentialElements = $container->findElements(
            WebDriverBy::xpath('descendant-or-self::*[self::input[@type="checkbox" or @type="radio"]]'),
        );

        $elements = [];

        foreach ($potentialElements as $element) {
            if ($this->isChecked($element)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        $potentialElements = $container->findElements(
            WebDriverBy::xpath('descendant-or-self::*[self::input[@type="checkbox" or @type="radio"]]'),
        );

        $elements = [];

        foreach ($potentialElements as $element) {
            if (!$this->isChecked($element)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    private function isChecked(WebDriverElement $container): bool
    {
        return $container->isSelected();
    }

    public function __toString(): string
    {
        return 'isChecked';
    }
}
