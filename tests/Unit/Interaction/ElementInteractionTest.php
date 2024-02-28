<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use EspressoWebDriver\Reporter\AssertionReporterInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;

use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;

#[CoversClass(AssertionFailedException::class)]
#[CoversClass(ElementInteraction::class)]
#[CoversClass(NoRootElementException::class)]
#[CoversClass(PerformException::class)]
class ElementInteractionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testCheckThrowsAssertionFailedExceptionWhenAssertReturnsFalse(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert match(mock)');

        // Arrange
        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter->expects($this->once())
            ->method('report')
            ->with(
                false,
                "Failed asserting that match(mock) is true, 1 element found for mock\nmock",
            );

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher->expects($this->once())
            ->method('__toString')
            ->willReturn('mock');
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockElementMatcher, [$mockElement]));

        $mockAssertion = $this->createMock(AssertionInterface::class);
        $mockAssertion->expects($this->once())
            ->method('assert')
            ->willReturn(false);
        $mockAssertion->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn('match(mock)');

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, null);

        // Act
        $interaction->check($mockAssertion);

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckThrowsAssertionFailedExceptionWhenNoElementIsFound(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert matches(isDisplayed), no element found for mock');

        // Arrange
        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter->expects($this->once())
            ->method('report')
            ->with(
                false,
                'Failed asserting that matches(isDisplayed) is true, no elements found for mock',
            );

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn('mock');
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockElementMatcher, []));

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, null);

        // Act
        $interaction->check(matches(isDisplayed()));

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckThrowsAssertionFailedExceptionWhenNoRootElementIsFound(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Failed to assert matches(isDisplayed), no root element found using withTagName(html)',
        );

        // Arrange
        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter->expects($this->once())
            ->method('report')
            ->with(
                false,
                'Failed asserting that matches(isDisplayed) is true, no root element found using withTagName(html)',
            );

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willThrowException(new NoSuchElementException(''));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, null);

        $mockAssertion = $this->createMock(AssertionInterface::class);
        $mockAssertion->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn('matches(isDisplayed)');

        // Act
        $interaction->check($mockAssertion);

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckThrowsAssertionExceptionWhenElementIsAmbiguous(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert matches(isDisplayed), 2 elements found for mock');

        // Arrange
        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter
            ->expects($this->once())
            ->method('report')
            ->with(
                false,
                'Failed asserting that matches(isDisplayed) is true, '
                ."2 elements found for mock\n"
                ."html/mock[1]\n"
                .'html/mock[2]',
            );

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $mockElementOne = $this->createMockWebDriverElement('mock');
        $mockElementTwo = $this->createMockWebDriverElement('mock');

        $this->createMockWebDriverElement('html', children: [
            $mockElementOne,
            $mockElementTwo,
        ]);

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher
            ->method('__toString')
            ->willReturn('mock');
        $mockElementMatcher
            ->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockElementMatcher, [$mockElementOne, $mockElementTwo]));

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, null);

        // Act
        $interaction->check(matches(isDisplayed()));

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckProvidesFluentInterface(): void
    {
        // Arrange
        /**
         * @var MockObject|WebDriver|JavaScriptExecutor $mockDriver
         */
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher
            ->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockElementMatcher, [$this->createMockWebDriverElement('mock')]));

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, null);

        $mockAssertion = $this->createMock(AssertionInterface::class);
        $mockAssertion
            ->method('assert')
            ->willReturn(true);

        // Act
        $result = $interaction->check($mockAssertion);

        // Assert
        $this->assertSame($interaction, $result);
    }

    public function testPerformThrowsExceptionOnFailureToPerform(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action mock on mock');

        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('__toString')
            ->willReturn('mock');

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction
            ->method('__toString')
            ->willReturn('mock');
        $mockAction
            ->expects($this->once())
            ->method('perform')
            ->willReturn(false);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockMatcher
            ->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockMatcher, [$mockElement]));

        $interaction = new ElementInteraction($mockMatcher, $mockContext, null);

        // Act
        $interaction->perform($mockAction);

        // Assert
        // No assertions, only expectations.
    }

    public function testPerformProvidesFluentInterface(): void
    {
        // Arrange
        /**
         * @var MockObject|WebDriver|JavaScriptExecutor $mockDriver
         */
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher
            ->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockElementMatcher, [$this->createMockWebDriverElement('mock')]));

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, null);

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction
            ->method('perform')
            ->willReturn(true);

        // Act
        $result = $interaction->perform($mockAction);

        // Assert
        $this->assertSame($interaction, $result);
    }

    public function testUsesContainerMatcherIfProvided(): void
    {
        // Arrange
        /**
         * @var MockObject|WebDriver|JavaScriptExecutor $mockDriver
         */
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockContainerMatcher = $this->createMock(MatcherInterface::class);
        $mockContainerMatcher
            ->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockContainerMatcher, [$this->createMockWebDriverElement('div')]));

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher
            ->expects($this->once())
            ->method('match')
            ->willReturn(new MatchResult($mockElementMatcher, [$this->createMockWebDriverElement('mock')]));

        $interaction = new ElementInteraction($mockElementMatcher, $mockContext, $mockContainerMatcher);

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction
            ->method('perform')
            ->willReturn(true);

        // Act
        $interaction->perform($mockAction);

        // Assert
        // No assertions, only expectations.
    }
}
