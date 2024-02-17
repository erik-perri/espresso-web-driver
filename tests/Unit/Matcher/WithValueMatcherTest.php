<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithValueMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithValueMatcher::class)]
class WithValueMatcherTest extends BaseUnitTestCase
{
    public function testWithValueToString(): void
    {
        // Arrange
        $matcher = new WithValueMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('withValue(mock)', $result);
    }
}
