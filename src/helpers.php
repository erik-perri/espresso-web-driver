<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace EspressoWebDriver;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Action\ClearTextAction;
use EspressoWebDriver\Action\ClickAction;
use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Action\TypeTextAction;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exceptions\NoMatchingElementException;
use EspressoWebDriver\Matcher\AllOfMatcher;
use EspressoWebDriver\Matcher\AnyOfMatcher;
use EspressoWebDriver\Matcher\HasDescendantMatcher;
use EspressoWebDriver\Matcher\HasFocusMatcher;
use EspressoWebDriver\Matcher\HasSiblingMatcher;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Matcher\IsEnabledMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\WithClassMatcher;
use EspressoWebDriver\Matcher\WithIdMatcher;
use EspressoWebDriver\Matcher\WithTagNameMatcher;
use EspressoWebDriver\Matcher\WithTextMatcher;
use Facebook\WebDriver\WebDriver;

// region Core

/**
 * @throws NoMatchingElementException
 */
function withDriver(WebDriver $driver, EspressoOptions $options = new EspressoOptions): EspressoCore
{
    return new EspressoCore($driver, $options);
}

// endregion

// region Actions

function clearText(): ActionInterface
{
    return new ClearTextAction();
}

function click(): ActionInterface
{
    return new ClickAction();
}

function submit(): ActionInterface
{
    return new SubmitAction();
}

function typeText(string $text): ActionInterface
{
    return new TypeTextAction($text);
}

// endregion

// region Assertions

function matches(MatcherInterface $assertion): AssertionInterface
{
    return new MatchesAssertion($assertion);
}

// endregion

// region Matchers

function allOf(MatcherInterface ...$matchers): MatcherInterface
{
    return new AllOfMatcher(...$matchers);
}

function anyOf(MatcherInterface ...$assertions): MatcherInterface
{
    return new AnyOfMatcher(...$assertions);
}

function hasDescendant(MatcherInterface $matcher): MatcherInterface
{
    return new HasDescendantMatcher($matcher);
}

function hasFocus(): MatcherInterface
{
    return new HasFocusMatcher();
}

function hasSibling(MatcherInterface $matcher): MatcherInterface
{
    return new HasSiblingMatcher($matcher);
}

function isDisplayed(): MatcherInterface
{
    return new IsDisplayedMatcher();
}

function isEnabled(): MatcherInterface
{
    return new IsEnabledMatcher();
}

function withClass(string $class): MatcherInterface
{
    return new WithClassMatcher($class);
}

function withId(string $id): MatcherInterface
{
    return new WithIdMatcher($id);
}

function withTagName(string $tagName): MatcherInterface
{
    return new WithTagNameMatcher($tagName);
}

function withText(string $text): MatcherInterface
{
    return new WithTextMatcher($text);
}

// endregion
