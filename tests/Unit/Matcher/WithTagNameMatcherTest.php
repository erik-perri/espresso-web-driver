<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithTagNameMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithTagNameMatcher::class)]
class WithTagNameMatcherTest extends BaseUnitTestCase
{
    public function testWithTagNameToString(): void
    {
        // Arrange
        $matcher = new WithTagNameMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('tagName="mock"', $result);
    }
}
