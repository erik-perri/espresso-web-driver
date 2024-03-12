<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithPlaceholderMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithPlaceholderMatcher::class)]
class WithPlaceholderMatcherTest extends BaseUnitTestCase
{
    public function testWithPlaceholderToString(): void
    {
        // Arrange
        $matcher = new WithPlaceholderMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('withPlaceholder(mock)', $result);
    }
}
