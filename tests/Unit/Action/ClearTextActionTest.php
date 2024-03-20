<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClearTextAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchResult;
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
        $mockContainer->expects($this->once())
            ->method('clear');

        $mockResult = new MatchResult(
            container: null,
            expectedCount: ExpectedMatchCount::One,
            matcher: $this->createMock(MatcherInterface::class),
            result: [$mockContainer],
        );

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions,
        );

        $action = new ClearTextAction;

        // Act
        $result = $action->perform($mockResult, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testTypeTextToString(): void
    {
        // Arrange
        $action = new ClearTextAction;

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('clearText', $result);
    }
}
