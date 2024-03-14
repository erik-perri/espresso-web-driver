<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\HasAncestorMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasAncestorMatcher::class)]
class HasAncestorMatcherTest extends BaseUnitTestCase
{
    public function testHasAncestorToString(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->expects($this->once())
            ->method('__toString')
            ->willReturn('mock');

        $matcher = new HasAncestorMatcher($mockMatcher);

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('hasAncestor(mock)', $result);
    }
}
