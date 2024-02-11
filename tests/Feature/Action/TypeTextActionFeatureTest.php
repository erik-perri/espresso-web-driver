<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Action;

use EspressoWebDriver\Action\TypeTextAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\typeText;
use function EspressoWebDriver\withDriver;
use function EspressoWebDriver\withId;

#[CoversFunction('EspressoWebDriver\typeText')]
#[CoversClass(TypeTextAction::class)]
class TypeTextActionFeatureTest extends BaseFeatureTestCase
{
    public function testTypesTextInExpectedInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/type-text.html'));

        $options = new EspressoOptions(waitTimeoutInSeconds: 0);

        $espresso = withDriver($driver, $options);

        // Act
        $espresso
            ->onElement(withId('test-a'))
            ->perform(click(), typeText('Value A'));

        $espresso
            ->onElement(withId('test-b'))
            ->perform(click(), typeText("Value B\nWith new line"));

        $espresso
            ->onElement(withId('test-c'))
            // Click twice to open and close the select for focus
            // TODO Add a way to focus elements like this
            ->perform(click(), click(), typeText('Value C'));

        // Assert
        $this->assertSame(
            'Value A',
            $driver->findElement(WebDriverBy::id('test-a'))->getAttribute('value'),
        );
        $this->assertSame(
            "Value B\nWith new line",
            $driver->findElement(WebDriverBy::id('test-b'))->getAttribute('value'),
        );
        $this->assertSame(
            'Value C',
            $driver->findElement(WebDriverBy::id('test-c'))->getAttribute('value'),
        );
    }
}
