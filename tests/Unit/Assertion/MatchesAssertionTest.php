<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Assertion;

use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MatchesAssertion::class)]
class MatchesAssertionTest extends BaseUnitTestCase
{
    public function testReturnsTrueIfMatcherResultIncludesContainer(): void
    {
        // Arrange
        $mockContainer = $this->createMock(WebDriverElement::class);
        $mockContainer
            ->method('getId')
            ->willReturn('mock');

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('match')
            ->willReturn([
                $this->createMock(WebDriverElement::class),
                $mockContainer,
                $this->createMock(WebDriverElement::class),
            ]);

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $assertion = new MatchesAssertion($mockMatcher);

        // Act
        $result = $assertion->assert($mockContainer, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testReturnsFalseIfMatcherResultDoesNotIncludeContainer(): void
    {
        // Arrange
        $mockContainer = $this->createMock(WebDriverElement::class);
        $mockContainer
            ->method('getId')
            ->willReturn('mock');

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('match')
            ->willReturn([
                $this->createMock(WebDriverElement::class),
            ]);

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $assertion = new MatchesAssertion($mockMatcher);

        // Act
        $result = $assertion->assert($mockContainer, $mockContext);

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
