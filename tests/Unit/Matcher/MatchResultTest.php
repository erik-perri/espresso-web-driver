<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Utilities\ElementPathLogger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AmbiguousElementException::class)]
#[CoversClass(MatchResult::class)]
#[CoversClass(NoMatchingElementException::class)]
class MatchResultTest extends TestCase
{
    use MocksWebDriverElement;

    public function testMatchResultArrayWrappers(): void
    {
        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);

        $elementOne = $this->createMockWebDriverElement('div');
        $elementTwo = $this->createMockWebDriverElement('div');

        $result = new MatchResult(
            $matcher,
            [
                $elementOne,
                $elementTwo,
            ],
            false,
        );

        // Act
        $all = $result->all();
        $count = $result->count();

        // Assert
        $this->assertSame([$elementOne, $elementTwo], $all);
        $this->assertSame(2, $count);
    }

    public function testMatchResultDescribe(): void
    {
        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher()');

        $element = $this->createMockWebDriverElement('mock');

        $result = new MatchResult(
            $matcher,
            [$element],
            false,
        );

        // Act
        $resultAsString = $result->describe(new ElementPathLogger());

        // Assert
        $this->assertSame("1 element found for matcher()\nmock", $resultAsString);
    }

    public function testMatchResultDescribeWhileEmpty(): void
    {
        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher()');

        $result = new MatchResult(
            $matcher,
            [],
        );

        // Act
        $resultAsString = $result->describe(new ElementPathLogger());

        // Assert
        $this->assertSame('no elements found for matcher()', $resultAsString);
    }

    public function testMatchResultThrowsAmbiguousElementMatcherException(): void
    {
        // Expectations
        $this->expectException(AmbiguousElementException::class);
        $this->expectExceptionMessage('2 elements found for matcher()');

        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher()');

        $elementOne = $this->createMockWebDriverElement('mock');
        $elementTwo = $this->createMockWebDriverElement('mock');

        $result = new MatchResult(
            $matcher,
            [
                $elementOne,
                $elementTwo,
            ],
            false,
        );

        // Act
        $result->single();

        // Assert
        // No assertions, only expectations.
    }

    public function testMatchResultThrowsNoMatchingElementException(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for matcher()');

        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher()');

        $result = new MatchResult(
            $matcher,
            [],
            false,
        );

        // Act
        $result->single();

        // Assert
        // No assertions, only expectations.
    }

    public function testMatchResultSingleReturnsOnlyElement(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockElement = $this->createMockWebDriverElement('mock');

        $result = new MatchResult(
            $mockMatcher,
            [$mockElement],
            false,
        );

        // Act
        $single = $result->single();

        // Assert
        $this->assertSame($mockElement, $single);
    }
}
