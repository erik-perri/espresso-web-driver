<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Processor;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ActionProcessor;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchResult;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ActionProcessor::class)]
class ActionProcessorTest extends BaseUnitTestCase
{
    public function testReturnsResultOfActionCall(): void
    {
        // Arrange
        $mockContext = new EspressoContext(
            driver: $this->createMock(WebDriver::class),
            options: new EspressoOptions,
        );

        $mockTarget = new MatchResult(
            container: null,
            expectedCount: ExpectedMatchCount::OneOrMore,
            matcher: $this->createMock(MatcherInterface::class),
            result: [],
        );

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction->expects($this->once())
            ->method('perform')
            ->with($mockTarget, $mockContext)
            ->willReturn(true);

        $processor = new ActionProcessor;

        // Act
        $result = $processor->process($mockAction, $mockTarget, $mockContext);

        // Assert
        $this->assertTrue($result);
    }
}
