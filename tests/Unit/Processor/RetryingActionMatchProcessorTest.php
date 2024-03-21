<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\RetryingActionProcessor;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\WebDriver;
use PharIo\Manifest\ElementCollectionException;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bridge\PhpUnit\ClockMock;

use function EspressoWebDriver\click;

#[CoversClass(RetryingActionProcessor::class)]
class RetryingActionMatchProcessorTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    protected function setUp(): void
    {
        ClockMock::register(RetryingActionProcessor::class);

        ClockMock::withClockMock(true);
    }

    protected function tearDown(): void
    {
        ClockMock::withClockMock(false);
    }

    public function testThrowsExceptionsThatWereThrownUntilTheEnd(): void
    {
        // Expectations
        $this->expectException(ElementClickInterceptedException::class);

        // Arrange
        $expectedRetries = 4;

        $mockElement = $this->createMockWebDriverElement('div');
        $mockElement->expects($this->exactly($expectedRetries + 1))
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
                actionProcessor: new RetryingActionProcessor($expectedRetries),
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
                actionProcessor: new RetryingActionProcessor,
            ),
        );

        $interaction = new ElementInteraction($mockMatcher, null, $mockContext);

        // Act
        $interaction->perform(click());

        // Assert
        // No assertions, only expectations.
    }
}
