<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoParentException;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;

final readonly class IsFocusedMatcher implements MatcherInterface, NegativeMatcherInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoParentException
     */
    public function match(MatchResult $container, EspressoContext $context): array
    {
        $containerElement = $container->single();

        try {
            $parent = $containerElement->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            // If we cannot find the parent there is no way for us to find the focused element.
            throw new NoParentException($this, $context->options->elementLogger->describe($containerElement));
        }

        return $parent->findElements(WebDriverBy::cssSelector(':focus'));
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoParentException
     */
    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        $containerElement = $container->single();

        try {
            $parent = $containerElement->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            // If we cannot find the parent there is no way for us to find the focused element.
            throw new NoParentException($this, $context->options->elementLogger->describe($containerElement));
        }

        return $parent->findElements(WebDriverBy::cssSelector(':not(:focus)'));
    }

    public function __toString(): string
    {
        return 'isFocused';
    }
}
