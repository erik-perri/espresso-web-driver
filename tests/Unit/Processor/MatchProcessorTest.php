<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchProcessor;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NoRootElementException::class)]
#[CoversClass(MatchProcessor::class)]
class MatchProcessorTest extends BaseUnitTestCase
{
    public function testThrowsAssertionFailedExceptionWhenNoRootElementIsFound(): void
    {
        // Expectations
        $this->expectException(NoRootElementException::class);
        $this->expectExceptionMessage(
            'no root element found using withTagName(html)',
        );

        // Arrange
        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver->expects($this->once())
            ->method('findElement')
            ->willThrowException(new NoSuchElementException(''));

        $mockContext = new EspressoContext(
            driver: $mockDriver,
            options: new EspressoOptions,
        );

        $mockTargetMatcher = $this->createMock(MatcherInterface::class);

        $processor = new MatchProcessor;

        // Act
        $processor->process($mockTargetMatcher, null, $mockContext, ExpectedMatchCount::OneOrMore);

        // Assert
        // No assertions, only expectations.
    }
}
