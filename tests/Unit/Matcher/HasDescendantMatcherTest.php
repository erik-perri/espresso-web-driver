<?php

/** @noinspection PhpUnhandledExceptionInspection */

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
        $mockMatcher->method('__toString')
            ->willReturn('mock(test)');

        $matcher = new HasDescendantMatcher($mockMatcher);

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('hasDescendant(mock(test))', $result);
    }
}
