<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SubmitAction::class)]
class SubmitActionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testSubmitToString(): void
    {
        // Arrange
        $action = new SubmitAction;

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('submit', $result);
    }

    public function testThrowsPerformExceptionWhenProvidedElementUnrelatedToForm(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action submit on mock, not a submittable element');

        // Arrange
        $action = new SubmitAction;

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockResult = new MatchResult(
            container: null,
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
