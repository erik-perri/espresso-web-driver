<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\AnyOfMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AnyOfMatcher::class)]
class AnyOfMatcherTest extends BaseUnitTestCase
{
    public function testAnyOfMatcherToString(): void
    {
        // Arrange
        $mockMatcherOne = $this->createMock(MatcherInterface::class);
        $mockMatcherOne
            ->method('__toString')
            ->willReturn('mock1');

        $mockMatcherTwo = $this->createMock(MatcherInterface::class);
        $mockMatcherTwo
            ->method('__toString')
            ->willReturn('mock2');

        $assertion = new AnyOfMatcher($mockMatcherOne, $mockMatcherTwo);

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('anyOf(mock1; mock2)', $result);
    }
}
