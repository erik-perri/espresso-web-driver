<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SubmitAction::class)]
class SubmitActionTest extends BaseUnitTestCase
{
    public function testSubmitToString(): void
    {
        // Arrange
        $assertion = new SubmitAction();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('submit', $result);
    }

    public function testThrowsPerformExceptionWhenProvidedElementUnrelatedToForm(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action submit on mock, not a form related element');

        // Arrange
        $assertion = new SubmitAction();

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
