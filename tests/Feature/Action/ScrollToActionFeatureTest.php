<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ScrollToAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\scrollTo;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withText;

#[CoversClass(ScrollToAction::class)]
#[CoversFunction('EspressoWebDriver\scrollTo')]
class ScrollToActionFeatureTest extends BaseFeatureTestCase
{
    public function testScrollToSpecifiedElement(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/scroll-to.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withText('Mock Z'))
            ->perform(scrollTo())
            ->check(matches(isDisplayed()));
    }
}
