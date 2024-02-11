<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithTextMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithTextMatcher::class)]
class WithTextMatcherTest extends BaseUnitTestCase
{
    public function testWithTextToString(): void
    {
        // Arrange
        $matcher = new WithTextMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('text="mock"', $result);
    }
}
