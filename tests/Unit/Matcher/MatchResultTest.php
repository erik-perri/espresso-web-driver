<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MatchResult::class)]
class MatchResultTest extends TestCase
{
    public function testMatchResultArrayWrappers()
    {
        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $elementOne = $this->createMock(WebDriverElement::class);
        $elementTwo = $this->createMock(WebDriverElement::class);

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

    public function testMatchResultToString()
    {
        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher');
        $element = $this->createMock(WebDriverElement::class);

        $result = new MatchResult(
            $matcher,
            [$element],
            false,
        );

        // Act
        $resultAsString = (string) $result;

        // Assert
        $this->assertSame('matcher (1 element)', $resultAsString);
    }

    public function testMatchResultThrowsAmbiguousElementMatcherException()
    {
        // Expectations
        $this->expectException(AmbiguousElementException::class);
        $this->expectExceptionMessage('2 elements found for matcher');

        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher');
        $elementOne = $this->createMock(WebDriverElement::class);
        $elementTwo = $this->createMock(WebDriverElement::class);

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

    public function testMatchResultThrowsNoMatchingElementException()
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for matcher');

        // Arrange
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('__toString')
            ->willReturn('matcher');

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
}
