<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsCheckedMatcher implements MatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $context->isNegated
            ? $this->matchUncheckedElements($container->single())
            : $this->matchCheckedElements($container->single());
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchCheckedElements(WebDriverElement $container): array
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

    /**
     * @return WebDriverElement[]
     */
    private function matchUncheckedElements(WebDriverElement $container): array
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
