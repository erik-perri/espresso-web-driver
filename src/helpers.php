<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace EspressoWebDriver;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Action\ClearTextAction;
use EspressoWebDriver\Action\ClickAction;
use EspressoWebDriver\Action\FocusAction;
use EspressoWebDriver\Action\ScrollToAction;
use EspressoWebDriver\Action\SendKeysAction;
use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Action\TypeTextAction;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Assertion\DoesNotExistAssertion;
use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\AllOfMatcher;
use EspressoWebDriver\Matcher\AnyOfMatcher;
use EspressoWebDriver\Matcher\HasDescendantMatcher;
use EspressoWebDriver\Matcher\HasFocusMatcher;
use EspressoWebDriver\Matcher\HasSiblingMatcher;
use EspressoWebDriver\Matcher\IsCheckedMatcher;
use EspressoWebDriver\Matcher\IsDisplayedInViewportMatcher;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Matcher\IsEnabledMatcher;
use EspressoWebDriver\Matcher\IsPresentMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\NotMatcher;
use EspressoWebDriver\Matcher\WithClassMatcher;
use EspressoWebDriver\Matcher\WithIdMatcher;
use EspressoWebDriver\Matcher\WithLabelMatcher;
use EspressoWebDriver\Matcher\WithTagNameMatcher;
use EspressoWebDriver\Matcher\WithTextContainingMatcher;
use EspressoWebDriver\Matcher\WithTextMatcher;
use EspressoWebDriver\Matcher\WithValueMatcher;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverKeys;

// region Core

/**
 * @throws NoMatchingElementException
 */
function usingDriver(WebDriver $driver, EspressoOptions $options = new EspressoOptions): EspressoCore
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

function focus(): ActionInterface
{
    return new FocusAction();
}

function scrollTo(): ActionInterface
{
    return new ScrollToAction();
}

/**
 * Sends the specified keys to the browser.
 *
 * @see WebDriverKeys
 */
function sendKeys(string ...$keys): ActionInterface
{
    return new SendKeysAction(...$keys);
}

function submit(): ActionInterface
{
    return new SubmitAction();
}

/**
 * Selects the matched element and types the given text.
 */
function typeText(string $text): ActionInterface
{
    return new TypeTextAction($text);
}

// endregion

// region Assertions

function doesNotExist(): AssertionInterface
{
    return new DoesNotExistAssertion();
}

function matches(MatcherInterface $matcher): AssertionInterface
{
    return new MatchesAssertion($matcher);
}

// endregion

// region Matchers

function allOf(MatcherInterface ...$matchers): MatcherInterface
{
    return new AllOfMatcher(...$matchers);
}

function anyOf(MatcherInterface ...$matchers): MatcherInterface
{
    return new AnyOfMatcher(...$matchers);
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

function isChecked(): MatcherInterface
{
    return new IsCheckedMatcher();
}

/**
 * Matches elements visible on the page (not necessarily within in the viewport).
 */
function isDisplayed(): MatcherInterface
{
    return new IsDisplayedMatcher();
}

/**
 * Matches elements visible on the page and in the viewport.
 */
function isDisplayedInViewport(): MatcherInterface
{
    return new IsDisplayedInViewportMatcher();
}

function isEnabled(): MatcherInterface
{
    return new IsEnabledMatcher();
}

/**
 * Matches elements that exist in the DOM, regardless of visibility.
 */
function isPresent(): MatcherInterface
{
    return new IsPresentMatcher();
}

function not(MatcherInterface $matcher): MatcherInterface
{
    return new NotMatcher($matcher);
}

function withClass(string $class): MatcherInterface
{
    return new WithClassMatcher($class);
}

function withId(string $id): MatcherInterface
{
    return new WithIdMatcher($id);
}

function withLabel(string $text): MatcherInterface
{
    return new WithLabelMatcher($text);
}

function withTagName(string $tagName): MatcherInterface
{
    return new WithTagNameMatcher($tagName);
}

function withText(string $text): MatcherInterface
{
    return new WithTextMatcher($text);
}

function withTextContaining(string $text): MatcherInterface
{
    return new WithTextContainingMatcher($text);
}

function withValue(string $value): MatcherInterface
{
    return new WithValueMatcher($value);
}

// endregion
