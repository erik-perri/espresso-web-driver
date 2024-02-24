<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ScrollToAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ScrollToAction::class)]
class ScrollToActionTest extends BaseUnitTestCase
{
    public function testScrollToToString(): void
    {
        // Arrange
        $assertion = new ScrollToAction();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('scrollTo', $result);
    }

    public function testThrowsPerformExceptionWhenMissingExecuteAccess(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action scrollTo on mock <mock>, driver does not have access to executeScript');

        // Arrange
        $assertion = new ScrollToAction();

        $mockElement = $this->createMock(WebDriverElement::class);
        $mockElement->method('getTagName')->willReturn('mock');

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(waitTimeoutInSeconds: 0),
        );

        // Act
        $assertion->perform($mockElement, $mockContext);

        // Assert
        // No assertions, only expectations.
    }
}
