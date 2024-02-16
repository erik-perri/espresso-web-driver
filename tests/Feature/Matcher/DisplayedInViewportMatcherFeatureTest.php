<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\DisplayedInViewportMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\displayedInViewport;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\scrollTo;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(DisplayedInViewportMatcher::class)]
#[CoversFunction('EspressoWebDriver\displayedInViewport')]
class DisplayedInViewportMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testCannotSeeElementsThatAreOutOfTheViewport(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/displayed.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        $topElement = $espresso->onElement(withText('Mock A'));
        $bottomElement = $espresso->onElement(withText('Mock Z'));

        // Act and Assert
        $topElement
            ->check(matches(displayedInViewport()));
        $bottomElement
            ->check(matches(not(displayedInViewport())))
            ->perform(scrollTo())
            ->check(matches(displayedInViewport()));

        $topElement
            ->check(matches(not(displayedInViewport())))
            ->perform(scrollTo())
            ->check(matches(displayedInViewport()));
        $bottomElement
            ->check(matches(not(displayedInViewport())));
    }

    public function testCannotSeeElementsThatAreHiddenWithCss(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/displayed.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        $targetElement = $espresso->onElement(withText('Mock C'));
        $hiddenUntilClickElement = $espresso->onElement(withClass('hidden'));

        // Act and Assert
        $hiddenUntilClickElement->check(matches(not(displayedInViewport())));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(displayedInViewport()));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(not(displayedInViewport())));
    }
}
