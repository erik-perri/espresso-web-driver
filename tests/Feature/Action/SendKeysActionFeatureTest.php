<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SendKeysAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\focus;
use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\sendKeys;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;

#[CoversClass(SendKeysAction::class)]
#[CoversFunction('EspressoWebDriver\sendKeys')]
class SendKeysActionFeatureTest extends BaseFeatureTestCase
{
    public function testSendsKeysToInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/send-keys.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso
            ->onElement(withId('test-a'))
            ->perform(focus(), sendKeys(WebDriverKeys::END, WebDriverKeys::BACKSPACE));

        // Assert
        $this->assertSame(
            'Value ',
            $driver->findElement(WebDriverBy::id('test-a'))->getAttribute('value'),
        );
    }

    public function testSendsKeysToBrowser(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/send-keys.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        $modal = $espresso->onElement(withClass('modal'));
        $button = $espresso->onElement(withTagName('button'));

        // Act and Assert
        $modal->check(matches(not(isDisplayed())));

        $button->perform(focus(), sendKeys(WebDriverKeys::SPACE));

        $modal->check(matches(isDisplayed()));

        $button->perform(sendKeys(WebDriverKeys::ESCAPE));

        $modal->check(matches(not(isDisplayed())));
    }
}
