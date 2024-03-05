<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoParentException;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsFocusedMatcher implements MatcherInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoParentException
     */
    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $context->isNegated
            ? $this->matchUnfocusedElements($container->single(), $context)
            : $this->matchFocusedElements($container->single(), $context);
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws NoParentException
     */
    private function matchFocusedElements(WebDriverElement $container, EspressoContext $context): array
    {
        try {
            $parent = $container->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            // If we cannot find the parent there is no way for us to find the focused element.
            throw new NoParentException($this, $context->options->elementLogger->describe($container));
        }

        return $parent->findElements(WebDriverBy::cssSelector(':focus'));
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws NoParentException
     */
    private function matchUnfocusedElements(WebDriverElement $container, EspressoContext $context): array
    {
        try {
            $parent = $container->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            // If we cannot find the parent there is no way for us to find the focused element.
            throw new NoParentException($this, $context->options->elementLogger->describe($container));
        }

        return $parent->findElements(WebDriverBy::cssSelector(':not(:focus)'));
    }

    public function __toString(): string
    {
        return 'isFocused';
    }
}
