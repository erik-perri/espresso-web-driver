<?php

declare(strict_types=1);

namespace EspressoWebDriver;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Action\ClickAction;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Matcher\AllOfMatcher;
use EspressoWebDriver\Matcher\AnyOfMatcher;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\WithIdMatcher;
use Facebook\WebDriver\WebDriver;

// region Core

function withDriver(WebDriver $driver): EspressoCore
{
    return new EspressoCore($driver);
}

// endregion

// region Actions

function click(): ActionInterface
{
    return new ClickAction();
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

function isDisplayed(): MatcherInterface
{
    return new IsDisplayedMatcher();
}

function withId(string $id): MatcherInterface
{
    return new WithIdMatcher($id);
}

// endregion
