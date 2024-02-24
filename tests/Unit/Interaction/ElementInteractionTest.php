<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use EspressoWebDriver\Reporter\AssertionReporterInterface;
use EspressoWebDriver\Tests\Helpers\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withTagName;

#[CoversClass(AssertionFailedException::class)]
#[CoversClass(ElementInteraction::class)]
#[CoversClass(PerformException::class)]
class ElementInteractionTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testCheckThrowsAssertionExceptionOnFailureDueToAssertReturningFalse(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert match(mock)');

        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('__toString')
            ->willReturn('mock');

        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter
            ->expects($this->once())
            ->method('report')
            ->with(
                false,
                "Failed asserting that match(mock) is true, 1 element found for mock\nmock",
            );

        $mockOptions = new EspressoOptions(assertionReporter: $reporter);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockResult = new MatchResult($mockMatcher, [
            $mockElement,
        ]);

        $mockAssertion = $this->createMock(AssertionInterface::class);
        $mockAssertion
            ->method('assert')
            ->willReturn(false);
        $mockAssertion
            ->method('__toString')
            ->willReturn('match(mock)');

        $interaction = new ElementInteraction($mockResult, $mockContext);

        // Act
        $interaction->check($mockAssertion);

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckThrowsAssertionExceptionOnFailureDueToNoElement(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert matches(isDisplayed), No element found for mock');

        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('__toString')
            ->willReturn('mock');

        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter
            ->expects($this->once())
            ->method('report')
            ->with(
                false,
                'Failed asserting that matches(isDisplayed) is true, no elements found for mock',
            );

        $mockOptions = new EspressoOptions(assertionReporter: $reporter);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $mockResult = new MatchResult($mockMatcher, []);

        $interaction = new ElementInteraction($mockResult, $mockContext);

        // Act
        $interaction->check(matches(isDisplayed()));

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckThrowsAssertionExceptionOnFailureDueToAmbiguousElement(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert matches(isDisplayed), 2 elements found for mock');

        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('__toString')
            ->willReturn('mock');

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

        $mockOptions = new EspressoOptions(assertionReporter: $reporter);

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $mockElementOne = $this->createMockWebDriverElement('mock');
        $mockElementTwo = $this->createMockWebDriverElement('mock');

        $this->createMockWebDriverElement('html', children: [
            $mockElementOne,
            $mockElementTwo,
        ]);

        $mockResult = new MatchResult($mockMatcher, [
            $mockElementOne,
            $mockElementTwo,
        ]);

        $interaction = new ElementInteraction($mockResult, $mockContext);

        // Act
        $interaction->check(matches(isDisplayed()));

        // Assert
        // No assertions, only expectations.
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

        $mockOptions = new EspressoOptions();

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: $mockOptions,
        );

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockResult = new MatchResult($mockMatcher, [
            $mockElement,
        ]);

        $interaction = new ElementInteraction($mockResult, $mockContext);

        // Act
        $interaction->perform($mockAction);

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

        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockElement = $this->createMockWebDriverElement('mock');
        $mockElement->expects($this->once())
            ->method('findElements')
            ->willReturn([$mockElement]);

        $mockOptions = new EspressoOptions();

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: $mockOptions,
        );

        $mockResult = new MatchResult($mockMatcher, [$mockElement]);

        $interaction = new ElementInteraction($mockResult, $mockContext);

        // Act
        $result = $interaction->check(matches(withTagName('mock')));

        // Assert
        $this->assertSame($interaction, $result);
    }

    public function testPerformProvidesFluentInterface(): void
    {
        // Arrange
        /**
         * @var MockObject|WebDriver|JavaScriptExecutor $mockDriver
         */
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);

        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockElement = $this->createMockWebDriverElement('button');
        $mockElement
            ->method('click')
            ->willReturn(true);

        $mockOptions = new EspressoOptions();

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: $mockOptions,
        );

        $mockResult = new MatchResult($mockMatcher, [$mockElement]);

        $interaction = new ElementInteraction($mockResult, $mockContext);

        // Act
        $result = $interaction->perform(click());

        // Assert
        $this->assertSame($interaction, $result);
    }
}
