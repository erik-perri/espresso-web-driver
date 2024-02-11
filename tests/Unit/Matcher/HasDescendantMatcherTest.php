<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\HasDescendantMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasDescendantMatcher::class)]
class HasDescendantMatcherTest extends BaseUnitTestCase
{
    public function testHasDescendantToString(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher
            ->method('__toString')
            ->willReturn('mock="test"');

        // Act
        $matcher = new HasDescendantMatcher($mockMatcher);

        // Assert
        $this->assertSame('descendant(mock="test")', (string) $matcher);
    }
}
