<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Processor\MatchResult;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsCheckedMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        return array_filter(
            $this->findCheckElements($container),
            fn (WebDriverElement $element) => $element->isSelected(),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return array_filter(
            $this->findCheckElements($container),
            fn (WebDriverElement $element) => !$element->isSelected(),
        );
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function findCheckElements(MatchResult $container): array
    {
        return $container->single()->findElements(
            WebDriverBy::xpath('descendant-or-self::*[self::input[@type="checkbox" or @type="radio"]]'),
        );
    }

    public function __toString(): string
    {
        return 'isChecked';
    }
}
