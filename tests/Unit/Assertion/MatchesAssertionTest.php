<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Assertion;

use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MatchesAssertion::class)]
class MatchesAssertionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testReturnsTrueIfMatcherResultIncludesContainer(): void
    {
        // Arrange
        $mockTarget = $this->createMockWebDriverElement('div', ['id' => 'mock']);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockTarget]));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('match')
            ->willReturn([$mockTarget]);

        $assertion = new MatchesAssertion($mockMatcher);

        // Act
        $result = $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testReturnsFalseIfMatcherResultDoesNotIncludeContainer(): void
    {
        // Arrange
        $mockTarget = $this->createMockWebDriverElement('div', ['id' => 'mock']);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockTarget]));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockNotTarget = $this->createMockWebDriverElement('div', ['id' => 'not-target']);

        $mockTargetMatcher = $this->createMock(MatcherInterface::class);
        $mockTargetMatcher
            ->method('match')
            ->willReturn([$mockTarget]);

        $mockAssertMatcher = $this->createMock(MatcherInterface::class);
        $mockAssertMatcher
            ->method('match')
            ->willReturn([$mockNotTarget]);

        $assertion = new MatchesAssertion($mockAssertMatcher);

        // Act
        $result = $assertion->assert($mockTargetMatcher, null, $mockContext);

        // Assert
        $this->assertFalse($result);
    }

    public function testMatchesAssertionToString(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('__toString')
            ->willReturn('mock="test"');

        $assertion = new MatchesAssertion($mockMatcher);

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('matches(mock="test")', $result);
    }
}
