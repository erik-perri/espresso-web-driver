<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasFocusMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchUnfocusedElements($container->single(), $context)
                : $this->matchFocusedElements($container->single(), $context),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchFocusedElements(WebDriverElement $container, MatchContext $context): array
    {
        try {
            $parent = $container->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            // If we cannot find the parent there is no way for us to find the focused element.
            // TODO Should this be an exception?
            return [];
        }

        return $parent->findElements(WebDriverBy::cssSelector(':focus'));
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchUnfocusedElements(WebDriverElement $container, MatchContext $context): array
    {
        try {
            $parent = $container->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            // If we cannot find the parent there is no way for us to find the focused element.
            // TODO Should this be an exception?
            return [$container];
        }

        return $parent->findElements(WebDriverBy::cssSelector(':not(:focus)'));
    }

    public function __toString(): string
    {
        return 'focused';
    }
}
