<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Assertion;

use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
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

        $mockOptions = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
        );

        $assertion = new MatchesAssertion($mockMatcher);

        // Act
        $result = $assertion->assert($mockContainer, $mockOptions);

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

        $mockOptions = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
        );

        $assertion = new MatchesAssertion($mockMatcher);

        // Act
        $result = $assertion->assert($mockContainer, $mockOptions);

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

        // Act
        $assertion = new MatchesAssertion($mockMatcher);

        // Assert
        $this->assertSame('matches(mock="test")', (string) $assertion);
    }
}