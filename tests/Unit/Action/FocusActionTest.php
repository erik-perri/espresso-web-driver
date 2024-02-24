<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\FocusAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FocusAction::class)]
class FocusActionTest extends BaseUnitTestCase
{
    public function testFocusToString(): void
    {
        // Arrange
        $assertion = new FocusAction();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('focus', $result);
    }

    public function testThrowsPerformExceptionWhenMissingExecuteAccess(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action focus on mock <mock>, driver does not have access to executeScript');

        // Arrange
        $assertion = new FocusAction();

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
