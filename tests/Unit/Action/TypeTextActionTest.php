<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Action;

use EspressoWebDriver\Action\TypeTextAction;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchResult;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TypeTextAction::class)]
class TypeTextActionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testTypesProvidedKeys(): void
    {
        // Arrange
        $mockContainer = $this->createMockWebDriverElement('input');
        $mockContainer->expects($this->once())
            ->method('sendKeys')
            ->with('Mock Keys');

        $mockResult = new MatchResult(
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $this->createMock(MatcherInterface::class),
            result: [$mockContainer],
        );

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions,
        );

        $action = new TypeTextAction('Mock Keys');

        // Act
        $result = $action->perform($mockResult, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testTypeTextToString(): void
    {
        // Arrange
        $action = new TypeTextAction('Mock Keys');

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('typeText(Mock Keys)', $result);
    }
}
