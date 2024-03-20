<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Processor;

use EspressoWebDriver\Assertion\DoesNotExistAssertion;
use EspressoWebDriver\Assertion\ExistsAssertion;
use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\RetryingActionProcessor;
use EspressoWebDriver\Processor\RetryingMatchProcessor;
use EspressoWebDriver\Processor\RetryingProcessor;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Facebook\WebDriver\WebDriver;
use PharIo\Manifest\ElementCollectionException;
use PHPUnit\Framework\Attributes\CoversClass;
use RuntimeException;
use Symfony\Bridge\PhpUnit\ClockMock;

use function EspressoWebDriver\click;

#[CoversClass(RetryingActionProcessor::class)]
#[CoversClass(RetryingMatchProcessor::class)]
#[CoversClass(RetryingProcessor::class)]
class RetryingProcessorTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    protected function setUp(): void
    {
        ClockMock::register(RetryingProcessor::class);

        ClockMock::withClockMock(true);
    }

    protected function tearDown(): void
    {
        ClockMock::withClockMock(false);
    }

    public function testRetriesTheExpectedAmountOfTimes(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert matches(isDisplayed)');

        // Arrange
        $configuredDelayInMilliseconds = 160;
        $configuredTimeInMilliseconds = 1000;
        $expectedRetries = (int) ceil($configuredTimeInMilliseconds / $configuredDelayInMilliseconds);

        $mockContainer = $this->createMockWebDriverElement('div');
        $mockContainer
            ->expects($this->exactly($expectedRetries))
            ->method('findElements')
            ->willReturn([]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockContainer]));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $assertion = new MatchesAssertion(new IsDisplayedMatcher);

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockContainer]);

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testReturnsEarlyOnSuccess(): void
    {
        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInMilliseconds = 1000;
        $expectedRetries = 4;

        $mockElement = $this->createMockWebDriverElement('div', children: []);
        $mockElement->expects($this->exactly($expectedRetries))
            ->method('isDisplayed')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                false,
                true,
            );

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockElement]);

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $assertion = new MatchesAssertion(new IsDisplayedMatcher);

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testRetriesForTheExpectedAmountOfTimesWhenNoElementsAreWanted(): void
    {
        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInMilliseconds = 1000;
        $expectedRetries = 4;

        $mockElement = $this->createMockWebDriverElement('div');

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->exactly($expectedRetries))
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->exactly($expectedRetries))
            ->method('match')
            ->willReturnOnConsecutiveCalls(
                [$mockElement],
                [$mockElement],
                [$mockElement],
                [],
            );

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $assertion = new DoesNotExistAssertion;

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testRetriesForTheExpectedAmountOfTimesWhenOnlyOneElementIsWanted(): void
    {
        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInMilliseconds = 1000;
        $expectedRetries = 4;

        $mockElement = $this->createMockWebDriverElement('div');
        $anotherMockElement = $this->createMockWebDriverElement('div');

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->exactly($expectedRetries))
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->exactly($expectedRetries))
            ->method('match')
            ->willReturnOnConsecutiveCalls(
                [],
                [$mockElement, $anotherMockElement],
                [],
                [$mockElement],
            );

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $assertion = new ExistsAssertion;

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testRetriesRetryableExceptions(): void
    {
        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInMilliseconds = 1000;
        $expectedRetries = 4;

        $mockElement = $this->createMockWebDriverElement('div');

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->exactly($expectedRetries))
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->exactly($expectedRetries))
            ->method('match')
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new StaleElementReferenceException('')),
                $this->throwException(new StaleElementReferenceException('')),
                $this->throwException(new StaleElementReferenceException('')),
                [$mockElement],
            );

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $assertion = new ExistsAssertion;

        // Act
        $assertion->assert($mockMatcher, null, $mockContext);

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionsThatWereThrownUntilTheEnd(): void
    {
        // Expectations
        $this->expectException(ElementClickInterceptedException::class);

        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInMilliseconds = 300;
        $expectedRetries = 4;

        $mockElement = $this->createMockWebDriverElement('div');
        $mockElement->expects($this->exactly($expectedRetries))
            ->method('click')
            ->willThrowException(new ElementClickInterceptedException(''));

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockElement]);

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                actionProcessor: new RetryingActionProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $interaction = new ElementInteraction($mockMatcher, null, $mockContext);

        // Act
        $interaction->perform(click());

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionsThatAreNotExpectedToRetry(): void
    {
        // Expectations
        $this->expectException(ElementCollectionException::class);

        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInMilliseconds = 300;

        $mockElement = $this->createMockWebDriverElement('div');
        $mockElement->expects($this->once())
            ->method('click')
            ->willThrowException(new ElementCollectionException(''));

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html', children: [$mockElement]));

        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$mockElement]);

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                actionProcessor: new RetryingActionProcessor(
                    waitTimeoutInMilliseconds: $configuredTimeInMilliseconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $interaction = new ElementInteraction($mockMatcher, null, $mockContext);

        // Act
        $interaction->perform(click());

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionWithExplanationWhenProvidedTimeoutWhichProducesNoResult(): void
    {
        // Expectations
        $this->expectExceptionMessage('No result processed. Ensure the wait timeout is greater than 0.');
        $this->expectException(RuntimeException::class);

        // Arrange
        $matchContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInMilliseconds: 0,
                    waitIntervalInMilliseconds: 0,
                ),
            ),
        );

        $assertion = new MatchesAssertion(new IsDisplayedMatcher);

        $mockMatcher = $this->createMock(MatcherInterface::class);

        // Act
        $assertion->assert($mockMatcher, null, $matchContext);
    }
}
