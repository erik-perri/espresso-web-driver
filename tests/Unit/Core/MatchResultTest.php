<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Core;

use Closure;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchResult;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Utilities\ElementPathLogger;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $matcher,
            result: [
                $elementOne,
                $elementTwo,
            ],
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
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $matcher,
            result: [$element],
        );

        // Act
        $resultAsString = $result->describe(new ElementPathLogger);

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
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $matcher,
            result: [],
        );

        // Act
        $resultAsString = $result->describe(new ElementPathLogger);

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
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $matcher,
            result: [
                $elementOne,
                $elementTwo,
            ],
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
        $this->expectExceptionMessage('no element found for matcher()');

        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher()');

        $result = new MatchResult(
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $matcher,
            result: [],
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
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $mockMatcher,
            result: [$mockElement],
        );

        // Act
        $single = $result->single();

        // Assert
        $this->assertSame($mockElement, $single);
    }

    #[DataProvider('provideMatchResultShouldRetry')]
    public function testMatchResultShouldRetry(
        ExpectedMatchCount $expectedCount,
        Closure $resultFactory,
        bool $expectedShouldRetry,
    ): void {
        // Arrange
        $result = new MatchResult(
            container: null,
            expectedCount: $expectedCount,
            matcher: $this->createMock(MatcherInterface::class),
            result: $resultFactory($this),
        );

        // Act
        $shouldRetry = $result->shouldRetry();

        // Assert
        $this->assertEquals($expectedShouldRetry, $shouldRetry);
    }

    /**
     * @return array<string, array{
     *     expectedCount: ExpectedMatchCount,
     *     resultFactory: Closure(self): WebDriverElement[],
     *     expectedShouldRetry: bool
     * }>
     */
    public static function provideMatchResultShouldRetry(): array
    {
        return [
            'one valid' => [
                'expectedCount' => ExpectedMatchCount::One,
                'resultFactory' => fn (self $self) => [
                    $self->createMockWebDriverElement('div'),
                ],
                'expectedShouldRetry' => false,
            ],
            'one invalid' => [
                'expectedCount' => ExpectedMatchCount::One,
                'resultFactory' => fn (self $self) => [],
                'expectedShouldRetry' => true,
            ],
            'one or more valid' => [
                'expectedCount' => ExpectedMatchCount::OneOrMore,
                'resultFactory' => fn (self $self) => [
                    $self->createMockWebDriverElement('div'),
                    $self->createMockWebDriverElement('div'),
                ],
                'expectedShouldRetry' => false,
            ],
            'one or more invalid' => [
                'expectedCount' => ExpectedMatchCount::OneOrMore,
                'resultFactory' => fn (self $self) => [],
                'expectedShouldRetry' => true,
            ],
            'two or more valid' => [
                'expectedCount' => ExpectedMatchCount::TwoOrMore,
                'resultFactory' => fn (self $self) => [
                    $self->createMockWebDriverElement('div'),
                    $self->createMockWebDriverElement('div'),
                ],
                'expectedShouldRetry' => false,
            ],
            'two or more invalid' => [
                'expectedCount' => ExpectedMatchCount::TwoOrMore,
                'resultFactory' => fn (self $self) => [
                    $self->createMockWebDriverElement('div'),
                ],
                'expectedShouldRetry' => true,
            ],
            'zero valid' => [
                'expectedCount' => ExpectedMatchCount::Zero,
                'resultFactory' => fn (self $self) => [],
                'expectedShouldRetry' => false,
            ],
            'zero invalid' => [
                'expectedCount' => ExpectedMatchCount::Zero,
                'resultFactory' => fn (self $self) => [
                    $self->createMockWebDriverElement('div'),
                ],
                'expectedShouldRetry' => true,
            ],
        ];
    }
}
