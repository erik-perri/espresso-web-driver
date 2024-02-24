<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\IsDisplayedInViewportMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayedInViewport;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\scrollTo;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(IsDisplayedInViewportMatcher::class)]
#[CoversFunction('EspressoWebDriver\isDisplayedInViewport')]
class IsDisplayedInViewportMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testCannotSeeElementsThatAreOutOfTheViewport(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-displayed.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        $topElement = $espresso->onElement(withText('Mock A'));
        $bottomElement = $espresso->onElement(withText('Mock Z'));

        // Act and Assert
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
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-displayed.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        $targetElement = $espresso->onElement(withText('Mock C'));
        $hiddenUntilClickElement = $espresso->onElement(withClass('hidden'));

        // Act and Assert
        $hiddenUntilClickElement->check(matches(not(isDisplayedInViewport())));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(isDisplayedInViewport()));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(not(isDisplayedInViewport())));
    }
}
