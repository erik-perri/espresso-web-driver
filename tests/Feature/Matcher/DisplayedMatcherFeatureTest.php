<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\DisplayedMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\displayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(DisplayedMatcher::class)]
#[CoversFunction('EspressoWebDriver\displayed')]
class DisplayedMatcherFeatureTest extends BaseFeatureTestCase
{
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
        $hiddenUntilClickElement->check(matches(not(displayed())));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(displayed()));

        $targetElement->perform(click());

        $hiddenUntilClickElement->check(matches(not(displayed())));
    }
}
