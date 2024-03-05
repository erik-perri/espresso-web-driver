<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Assertion;

use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
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
        $mockContainer = $this->createMockWebDriverElement('div', ['id' => 'mock']);

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('match')
            ->willReturn([
                $this->createMockWebDriverElement('div'),
                $mockContainer,
                $this->createMockWebDriverElement('div'),
            ]);

        $mockOptions = new EspressoOptions();

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $assertion = new MatchesAssertion($mockMatcher);

        $mockResult = new MatchResult($mockMatcher, [$mockContainer]);

        // Act
        $result = $assertion->assert($mockResult, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testReturnsFalseIfMatcherResultDoesNotIncludeContainer(): void
    {
        // Arrange
        $mockContainer = $this->createMockWebDriverElement('div', ['id' => 'mock']);

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('match')
            ->willReturn([
                $this->createMockWebDriverElement('div'),
            ]);

        $mockOptions = new EspressoOptions();

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $assertion = new MatchesAssertion($mockMatcher);

        $mockResult = new MatchResult($mockMatcher, [$mockContainer]);

        // Act
        $result = $assertion->assert($mockResult, $mockContext);

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
