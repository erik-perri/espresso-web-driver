<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ScrollToAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\isDisplayedInViewport;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\scrollTo;
use function EspressoWebDriver\withText;

#[CoversClass(ScrollToAction::class)]
#[CoversFunction('EspressoWebDriver\scrollTo')]
class ScrollToActionFeatureTest extends BaseFeatureTestCase
{
    public function testScrollToSpecifiedElement(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/actions/scroll-to.html')
            ->onElement(withText('Mock Z'))
            ->perform(scrollTo())
            ->check(matches(isDisplayedInViewport()));
    }
}
