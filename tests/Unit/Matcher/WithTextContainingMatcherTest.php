<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithTextContainingMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithTextContainingMatcher::class)]
class WithTextContainingMatcherTest extends BaseUnitTestCase
{
    public function testWithTextContainingToString(): void
    {
        // Arrange
        $matcher = new WithTextContainingMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('textContaining="mock"', $result);
    }
}
