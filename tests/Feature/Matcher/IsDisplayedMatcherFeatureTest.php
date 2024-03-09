<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(IsDisplayedMatcher::class)]
#[CoversFunction('EspressoWebDriver\isDisplayed')]
class IsDisplayedMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testCannotSeeElementsThatAreHiddenWithCss(): void
    {
        // Arrange
        $espresso = $this->espresso();

        $targetElement = $espresso->onElement(withText('Mock C'));
        $hiddenUntilClickElement = $espresso->onElement(withClass('hidden'));

        // Act and Assert
        $espresso->navigateTo('/matchers/is-displayed.html');

        $hiddenUntilClickElement->check(matches(not(isDisplayed())));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(isDisplayed()));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(not(isDisplayed())));
    }
}
