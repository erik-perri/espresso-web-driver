<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ScrollToAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ScrollToAction::class)]
class ScrollToActionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testScrollToToString(): void
    {
        // Arrange
        $action = new ScrollToAction();

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('scrollTo', $result);
    }

    public function testThrowsPerformExceptionWhenMissingExecuteAccess(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action scrollTo on mock, driver does not have access to executeScript');

        // Arrange
        $action = new ScrollToAction();

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
