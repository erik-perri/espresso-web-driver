<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Core;

use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Utilities\ElementPathLogger;
use Facebook\WebDriver\WebDriverBy;
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
            container: null,
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
            matcher: $mockMatcher,
            result: [$mockElement],
        );

        // Act
        $single = $result->single();

        // Assert
        $this->assertSame($mockElement, $single);
    }

    public function testFindElementsReturnsUniqueElementsFromAllElements(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockElementOne = $this->createMockWebDriverElement('mock');
        $mockElementTwo = $this->createMockWebDriverElement('mock');
        $mockElementThree = $this->createMockWebDriverElement('mock');

        $mockContainerElementOne = $this->createMockWebDriverElement(
            'div',
            children: [$mockElementOne, $mockElementTwo],
        );
        $mockContainerElementTwo = $this->createMockWebDriverElement(
            'div',
            children: [$mockElementOne, $mockElementThree],
        );

        $result = new MatchResult(
            container: null,
            matcher: $mockMatcher,
            result: [
                $mockContainerElementOne,
                $mockContainerElementTwo,
            ],
        );

        // Act
        $all = $result->findElements(WebDriverBy::cssSelector('is-mocked-does-not-matter'));

        // Assert
        $this->assertSame([$mockElementOne, $mockElementTwo, $mockElementThree], $all);
    }
}
