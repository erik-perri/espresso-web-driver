<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\IsFocusedMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsFocusedMatcher::class)]
class IsFocusedMatcherTest extends BaseUnitTestCase
{
    public function testIsFocusedToString(): void
    {
        // Arrange
        $matcher = new IsFocusedMatcher;

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('isFocused', $result);
    }
}
