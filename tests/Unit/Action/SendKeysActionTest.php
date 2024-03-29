<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SendKeysAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchResult;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PerformException::class)]
#[CoversClass(SendKeysAction::class)]
class SendKeysActionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testClickToStringWithKeys(): void
    {
        // Arrange
        $action = new SendKeysAction(WebDriverKeys::DELETE, WebDriverKeys::END);

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('sendKeys(DELETE, END)', $result);
    }

    public function testClickToStringWithStrings(): void
    {
        // Arrange
        $action = new SendKeysAction('use', 'type', 'text');

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('sendKeys(use, type, text)', $result);
    }

    public function testThrowsIfDriverDoesNotHaveInputAccess(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage(
            'Failed to perform action sendKeys() on textarea, driver does not have access to input devices',
        );

        // Arrange
        $action = new SendKeysAction;

        $mockElement = $this->createMockWebDriverElement('textarea');

        $mockResult = new MatchResult(
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $this->createMock(MatcherInterface::class),
            result: [$mockElement],
        );

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions,
        );

        // Act
        $action->perform($mockResult, $mockContext);

        // Assert
        // No assertions, only expectations.
    }
}
