<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Processor;

use EspressoWebDriver\Assertion\MatchesAssertion;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\IsDisplayedMatcher;
use EspressoWebDriver\Matcher\MatchResult;
use EspressoWebDriver\Processor\RetryingMatchProcessor;
use EspressoWebDriver\Tests\Helpers\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bridge\PhpUnit\ClockMock;

use function EspressoWebDriver\withTagName;

#[CoversClass(RetryingMatchProcessor::class)]
class RetryingMatchProcessorTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    protected function setUp(): void
    {
        ClockMock::register(RetryingMatchProcessor::class);

        ClockMock::withClockMock(true);
    }

    protected function tearDown(): void
    {
        ClockMock::withClockMock(false);
    }

    public function testRetriesTheExpectedAmountOfTimes(): void
    {
        // Arrange
        $configuredDelayInMilliseconds = 160;
        $configuredTimeInSeconds = 1;
        $expectedRetries = (int) ceil(($configuredTimeInSeconds * 1000) / $configuredDelayInMilliseconds);

        $mockDriver = $this->createMock(WebDriver::class);

        $mockContainer = $this->createMockWebDriverElement('div');
        $mockContainer
            ->expects($this->exactly($expectedRetries))
            ->method('findElements')
            ->willReturn([]);

        $matchResult = new MatchResult(
            matcher: withTagName('html'),
            result: [$mockContainer],
        );

        $matchContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInSeconds: $configuredTimeInSeconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $matcher = new IsDisplayedMatcher();

        $assertion = new MatchesAssertion($matcher);

        // Act
        $result = $assertion->assert($matchResult, $matchContext);

        // Assert
        $this->assertFalse($result);
    }

    public function testReturnsEarlyOnSuccess(): void
    {
        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInSeconds = 1;
        $expectedRetries = 4;

        $mockDriver = $this->createMock(WebDriver::class);

        $mockElement = $this->createMockWebDriverElement('div', children: []);
        $mockElement->expects($this->exactly($expectedRetries))
            ->method('isDisplayed')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
                false,
                true,
            );

        $matchResult = new MatchResult(
            matcher: withTagName('div'),
            result: [$mockElement],
        );

        $matchContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions(
                matchProcessor: new RetryingMatchProcessor(
                    waitTimeoutInSeconds: $configuredTimeInSeconds,
                    waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
                ),
            ),
        );

        $matcher = new IsDisplayedMatcher();

        $assertion = new MatchesAssertion($matcher);

        // Act
        $result = $assertion->assert($matchResult, $matchContext);

        // Assert
        $this->assertTrue($result);
    }
}
