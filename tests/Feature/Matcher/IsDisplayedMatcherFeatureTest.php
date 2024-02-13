<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\scrollTo;
use function EspressoWebDriver\withDriver;
use function EspressoWebDriver\withText;

#[CoversClass(IsDisplayedMatcher::class)]
#[CoversFunction('EspressoWebDriver\isDisplayed')]
class IsDisplayedMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testCannotSeeElementsThatAreOutOfTheViewport(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-displayed.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        $topElement = $espresso->onElement(withText('Mock A'));
        $bottomElement = $espresso->onElement(withText('Mock Z'));

        // Act and Assert
        $topElement
            ->check(matches(isDisplayed()));
        $bottomElement
            ->check(matches(not(isDisplayed())))
            ->perform(scrollTo())
            ->check(matches(isDisplayed()));

        $topElement
            ->check(matches(not(isDisplayed())))
            ->perform(scrollTo())
            ->check(matches(isDisplayed()));
        $bottomElement
            ->check(matches(not(isDisplayed())));
    }

    public function testCannotSeeElementsThatAreHiddenWithCss(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-displayed.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        $targetElement = $espresso->onElement(withText('Mock C'));
        $hiddenUntilClickElement = $espresso->onElement(withText('Hidden'));

        // Act and Assert
        $hiddenUntilClickElement->check(matches(not(isDisplayed())));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(isDisplayed()));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(not(isDisplayed())));
    }
}
