<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Reporter\AssertionReporterInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

use function EspressoWebDriver\exists;
use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;

#[CoversClass(AssertionFailedException::class)]
#[CoversClass(ElementInteraction::class)]
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
                'Failed asserting that match(mock) is true',
            );

        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);

        $mockAssertion = $this->createMock(AssertionInterface::class);
        $mockAssertion->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn('match(mock)');
        $mockAssertion->expects($this->once())
            ->method('assert')
            ->willThrowException(new AssertionFailedException($mockAssertion));

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

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
                'Failed asserting that matches(isDisplayed) is true, no element found for mock',
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
        $mockElementMatcher->expects($this->once())
            ->method('__toString')
            ->willReturn('mock');
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->willReturn([]);

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

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

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

        $mockAssertion = new MatchesAssertion(new IsDisplayedMatcher);

        // Act
        $interaction->check($mockAssertion);

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckThrowsAssertionExceptionWhenElementIsAmbiguous(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert exists, 2 elements found for mock');

        // Arrange
        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter->expects($this->once())
            ->method('report')
            ->with(
                false,
                'Failed asserting that exists is true, '
                ."2 elements found for mock\n"
                ."html/mock[1]\n"
                .'html/mock[2]',
            );

        $mockElementOne = $this->createMockWebDriverElement('mock');
        $mockElementTwo = $this->createMockWebDriverElement('mock');

        $htmlElement = $this->createMockWebDriverElement('html', children: [
            $mockElementOne,
            $mockElementTwo,
        ]);

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher->method('__toString')
            ->willReturn('mock');
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockElementOne, $mockElementTwo]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($htmlElement);

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

        // Act
        $interaction->check(exists());

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckIncludesUnknownExceptionsInMessage(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert exists, mock exception');

        // Arrange
        $reporter = $this->createMock(AssertionReporterInterface::class);
        $reporter->expects($this->once())
            ->method('report')
            ->with(
                false,
                'Failed asserting that exists is true, Unexpected exception: mock exception',
            );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willThrowException(new \RuntimeException('mock exception'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(assertionReporter: $reporter),
        );

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

        // Act
        $interaction->check(exists());

        // Assert
        // No assertions, only expectations.
    }

    public function testCheckProvidesFluentInterface(): void
    {
        // Arrange
        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions,
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

        $mockAssertion = $this->createMock(AssertionInterface::class);

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
        $mockMatcher->method('__toString')
            ->willReturn('mock');

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction
            ->method('__toString')
            ->willReturn('mock');
        $mockAction->expects($this->once())
            ->method('perform')
            ->willReturn(false);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockElement = $this->createMockWebDriverElement('mock');

        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockElement]);

        $interaction = new ElementInteraction($mockMatcher, null, $mockContext);

        // Act
        $interaction->perform($mockAction);

        // Assert
        // No assertions, only expectations.
    }

    public function testPerformThrowsExceptionOnFailureToMatch(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action mock, no element found for mock');

        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('__toString')
            ->willReturn('mock');
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([]);

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction->expects($this->once())
            ->method('__toString')
            ->willReturn('mock');

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $interaction = new ElementInteraction($mockMatcher, null, $mockContext);

        // Act
        $interaction->perform($mockAction);

        // Assert
        // No assertions, only expectations.
    }

    public function testPerformProvidesFluentInterface(): void
    {
        // Arrange
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        /**
         * @var WebDriver $mockDriver
         */
        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$this->createMockWebDriverElement('mock')]);

        $interaction = new ElementInteraction($mockElementMatcher, null, $mockContext);

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
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        /**
         * @var WebDriver $mockDriver
         */
        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockContainerMatcher = $this->createMock(MatcherInterface::class);
        $mockContainerMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$this->createMockWebDriverElement('div')]);

        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$this->createMockWebDriverElement('mock')]);

        $interaction = new ElementInteraction($mockElementMatcher, $mockContainerMatcher, $mockContext);

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
