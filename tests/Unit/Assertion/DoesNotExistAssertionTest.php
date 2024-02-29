<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Assertion;

use EspressoWebDriver\Assertion\DoesNotExistAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
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
        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(),
        );

        $assertion = new DoesNotExistAssertion();

        $mockResult = new MatchResult($mockMatcher, []);

        // Act
        $result = $assertion->assert($mockResult, $mockContext);

        // Assert
        $this->assertTrue($result);
    }

    public function testReturnsFalseIfElementsExist(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(),
        );

        $assertion = new DoesNotExistAssertion();

        $mockResult = new MatchResult($mockMatcher, [$this->createMockWebDriverElement('div')]);

        // Act
        $result = $assertion->assert($mockResult, $mockContext);

        // Assert
        $this->assertFalse($result);
    }

    public function testDoesNotExistAssertionToString(): void
    {
        // Arrange
        $assertion = new DoesNotExistAssertion();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('doesNotExist', $result);
    }
}
