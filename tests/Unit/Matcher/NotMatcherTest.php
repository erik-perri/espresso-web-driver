<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\NotMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NotMatcher::class)]
class NotMatcherTest extends BaseUnitTestCase
{
    public function testNotToString(): void
    {
        // Arrange
        $innerMatcher = $this->createMock(MatcherInterface::class);
        $innerMatcher->method('__toString')->willReturn('mock');

        $matcher = new NotMatcher($innerMatcher);

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('not(mock)', $result);
    }
}
