<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\HasSiblingMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasSiblingMatcher::class)]
class HasSiblingMatcherTest extends BaseUnitTestCase
{
    public function testHasSiblingToString(): void
    {
        // Arrange
        $innerMatcher = $this->createMock(MatcherInterface::class);
        $innerMatcher->method('__toString')->willReturn('mock');

        $matcher = new HasSiblingMatcher($innerMatcher);

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('sibling(mock)', $result);
    }
}
