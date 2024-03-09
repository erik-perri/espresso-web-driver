<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClickAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayedInViewport;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\sendKeys;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withValue;

#[CoversClass(ClickAction::class)]
#[CoversFunction('EspressoWebDriver\click')]
class ClickActionFeatureTest extends BaseFeatureTestCase
{
    public function testSelectsInputsOnClick(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/actions/click.html')
            ->onElement(withId('test-a'))
            ->perform(click(), sendKeys(WebDriverKeys::HOME, WebDriverKeys::DELETE))
            ->check(matches(withValue('alue A')));
    }

    public function testPressesButtonsOnClick(): void
    {
        // Arrange
        $espresso = $this->espresso();

        $modal = $espresso->onElement(withClass('modal'));
        $button = $espresso->onElement(withTagName('button'));

        // Act and Assert
        $espresso->navigateTo('/actions/click.html');

        $modal->check(matches(not(isDisplayedInViewport())));

        $button->perform(click());

        $modal->check(matches(isDisplayedInViewport()));

        $button->perform(click());

        $modal->check(matches(not(isDisplayedInViewport())));
    }
}
