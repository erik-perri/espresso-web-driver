<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\IsDisplayedInViewportMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayedInViewport;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\scrollTo;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(IsDisplayedInViewportMatcher::class)]
#[CoversFunction('EspressoWebDriver\isDisplayedInViewport')]
class IsDisplayedInViewportMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testCannotSeeElementsThatAreOutOfTheViewport(): void
    {
        // Arrange
        $espresso = $this->espresso();

        $topElement = $espresso->onElement(withText('Mock A'));
        $bottomElement = $espresso->onElement(withText('Mock Z'));

        // Act and Assert
        $espresso->navigateTo('/matchers/is-displayed.html');

        $topElement
            ->check(matches(isDisplayedInViewport()));
        $bottomElement
            ->check(matches(not(isDisplayedInViewport())))
            ->perform(scrollTo())
            ->check(matches(isDisplayedInViewport()));

        $topElement
            ->check(matches(not(isDisplayedInViewport())))
            ->perform(scrollTo())
            ->check(matches(isDisplayedInViewport()));
        $bottomElement
            ->check(matches(not(isDisplayedInViewport())));
    }

    public function testCannotSeeElementsThatAreHiddenWithCss(): void
    {
        // Arrange
        $espresso = $this->espresso();

        $targetElement = $espresso->onElement(withText('Mock C'));
        $hiddenUntilClickElement = $espresso->onElement(withClass('hidden'));

        // Act and Assert
        $espresso->navigateTo('/matchers/is-displayed.html');

        $hiddenUntilClickElement->check(matches(not(isDisplayedInViewport())));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(isDisplayedInViewport()));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(not(isDisplayedInViewport())));
    }
}
