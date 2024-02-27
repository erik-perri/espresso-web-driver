<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\FocusAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Tests\Helpers\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FocusAction::class)]
class FocusActionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testFocusToString(): void
    {
        // Arrange
        $action = new FocusAction();

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('focus', $result);
    }

    public function testThrowsPerformExceptionWhenMissingExecuteAccess(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action focus on mock, driver does not have access to executeScript');

        // Arrange
        $action = new FocusAction();

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(),
        );

        // Act
        $action->perform($mockElement, $mockContext);

        // Assert
        // No assertions, only expectations.
    }
}
