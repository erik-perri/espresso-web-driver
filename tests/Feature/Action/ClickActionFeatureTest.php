<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClickAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayedInViewport;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\sendKeys;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;

#[CoversClass(ClickAction::class)]
#[CoversFunction('EspressoWebDriver\click')]
class ClickActionFeatureTest extends BaseFeatureTestCase
{
    public function testSelectsInputsOnClick(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/click.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso
            ->onElement(withId('test-a'))
            ->perform(click(), sendKeys(WebDriverKeys::HOME, WebDriverKeys::DELETE));

        // Assert
        $this->assertSame(
            'alue A',
            $driver->findElement(WebDriverBy::id('test-a'))->getAttribute('value'),
        );
    }

    public function testPressesButtonsOnClick(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/click.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        $modal = $espresso->onElement(withClass('modal'));
        $button = $espresso->onElement(withTagName('button'));

        // Act and Assert
        $modal->check(matches(not(isDisplayedInViewport())));

        $button->perform(click());

        $modal->check(matches(isDisplayedInViewport()));

        $button->perform(click());

        $modal->check(matches(not(isDisplayedInViewport())));
    }
}
