<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\CommonAncestorOfMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CommonAncestorOfMatcher::class)]
class CommonAncestorOfMatcherTest extends BaseUnitTestCase
{
    public function testCommonAncestorOfMatcherToString(): void
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

        $matcher = new CommonAncestorOfMatcher($mockMatcherOne, $mockMatcherTwo);

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('commonAncestorOf(mock1; mock2)', $result);
    }
}
