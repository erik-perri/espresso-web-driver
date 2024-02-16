<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Traits;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\DisplayedMatcher;
use EspressoWebDriver\Matcher\MatchContext;
use EspressoWebDriver\Matcher\MatchResult;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bridge\PhpUnit\ClockMock;

use function EspressoWebDriver\withTagName;

#[CoversClass(DisplayedMatcher::class)]
class HasAutomaticWaitTest extends BaseUnitTestCase
{
    protected function setUp(): void
    {
        ClockMock::register(HasAutomaticWait::class);

        ClockMock::withClockMock(true);
    }

    protected function tearDown(): void
    {
        ClockMock::withClockMock(false);
    }

    public function testRetriesTheExpectedAmountOfTimes()
    {
        // Arrange
        $configuredDelayInMilliseconds = 160;
        $configuredTimeInSeconds = 1;
        $expectedRetries = (int) ceil(($configuredTimeInSeconds * 1000) / $configuredDelayInMilliseconds);

        $mockDriver = $this->createMock(WebDriver::class);

        $mockContainer = $this->createMock(WebDriverElement::class);
        $mockContainer
            ->expects($this->exactly($expectedRetries))
            ->method('findElements')
            ->willReturn([]);

        $matchResult = new MatchResult(
            matcher: withTagName('html'),
            result: [$mockContainer],
        );

        $matchContext = new MatchContext(
            driver: $mockDriver,
            isNegated: false,
            options: new EspressoOptions(
                waitTimeoutInSeconds: $configuredTimeInSeconds,
                waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
            ),
        );

        $matcher = new DisplayedMatcher();

        // Act
        $result = $matcher->match($matchResult, $matchContext);

        // Assert
        $this->assertEquals(0, $result->count());
    }

    public function testReturnsEarlyOnSuccess()
    {
        // Arrange
        $configuredDelayInMilliseconds = 100;
        $configuredTimeInSeconds = 1;
        $expectedRetries = 4;

        $mockDriver = $this->createMock(WebDriver::class);

        $mockElement = $this->createMock(WebDriverElement::class);
        $mockElement
            ->method('isDisplayed')
            ->willReturn(true);

        $mockContainer = $this->createMock(WebDriverElement::class);
        $mockContainer
            ->expects($this->exactly($expectedRetries))
            ->method('findElements')
            ->willReturnOnConsecutiveCalls(
                [],
                [],
                [],
                [$mockElement],
            );

        $matchResult = new MatchResult(
            matcher: withTagName('html'),
            result: [$mockContainer],
        );

        $matchContext = new MatchContext(
            driver: $mockDriver,
            isNegated: false,
            options: new EspressoOptions(
                waitTimeoutInSeconds: $configuredTimeInSeconds,
                waitIntervalInMilliseconds: $configuredDelayInMilliseconds,
            ),
        );

        $matcher = new DisplayedMatcher();

        // Act
        $result = $matcher->match($matchResult, $matchContext);

        // Assert
        $this->assertEquals(1, $result->count());
    }
}
