<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClearTextAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ClearTextAction::class)]
class ClearTextActionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testClearsProvidedElement(): void
    {
        // Arrange
        $mockContainer = $this->createMockWebDriverElement('input');
        $mockContainer
            ->expects($this->once())
            ->method('clear');

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(),
        );

        $action = new ClearTextAction();

        // Act
        $result = $action->perform($mockContainer, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testTypeTextToString(): void
    {
        // Arrange
        $action = new ClearTextAction();

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('clearText', $result);
    }
}
