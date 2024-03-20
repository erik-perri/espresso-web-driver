<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\HasParentMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasParentMatcher::class)]
class HasParentMatcherTest extends BaseUnitTestCase
{
    public function testHasDescendantToString(): void
    {
        // Arrange
        $mockMatcher = $this->createMock(MatcherInterface::class);
        $mockMatcher->method('__toString')
            ->willReturn('mock(test)');

        $matcher = new HasParentMatcher($mockMatcher);

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('hasParent(mock(test))', $result);
    }
}
