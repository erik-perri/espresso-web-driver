<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClearTextAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\clearText;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\typeText;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withValue;

#[CoversClass(ClearTextAction::class)]
#[CoversFunction('EspressoWebDriver\clearText')]
class ClearTextActionFeatureTest extends BaseFeatureTestCase
{
    public function testClearsTextInTextInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/clear-text.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso
            ->onElement(withId('test-a'))
            ->perform(typeText('Value A'))
            ->check(matches(withValue('Value A')))
            ->perform(clearText());

        // Assert
        $this->assertSame(
            '',
            $driver->findElement(WebDriverBy::id('test-a'))->getAttribute('value'),
        );
    }

    public function testClearsTextInTextareaInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/clear-text.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso
            ->onElement(withId('test-b'))
            ->perform(clearText())
            ->check(matches(withValue('')))
            ->perform(typeText('Value B\nWith new line'))
            ->check(matches(withValue('Value B\nWith new line')))
            ->perform(clearText());

        // Assert
        $this->assertSame(
            '',
            $driver->findElement(WebDriverBy::id('test-b'))->getAttribute('value'),
        );
    }
}
