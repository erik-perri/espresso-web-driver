<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        return array_filter(
            $this->findPotentialElements($container),
            fn (WebDriverElement $element) => $element->isDisplayed(),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return array_filter(
            $this->findPotentialElements($container),
            fn (WebDriverElement $element) => !$element->isDisplayed(),
        );
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function findPotentialElements(MatchResult $container): array
    {
        $element = $container->single();

        return [
            $element,
            // TODO This is probably a bad idea on dom heavy pages
            ...$element->findElements(WebDriverBy::cssSelector('*')),
        ];
    }

    public function __toString(): string
    {
        return 'isDisplayed';
    }
}
