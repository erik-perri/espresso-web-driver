<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Assertion;

use EspressoWebDriver\Assertion\DoesNotExistAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DoesNotExistAssertion::class)]
class DoesNotExistAssertionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testReturnsTrueIfNoMatchesWereFound(): void
    {
        // Arrange
        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $assertion = new DoesNotExistAssertion;

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([]);

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionIfElementsExist(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert doesNotExist');

        // Arrange
        $mockElement = $this->createMockWebDriverElement('div');

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $assertion = new DoesNotExistAssertion;

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockElement]);

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testDoesNotExistAssertionToString(): void
    {
        // Arrange
        $assertion = new DoesNotExistAssertion;

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('doesNotExist', $result);
    }
}
