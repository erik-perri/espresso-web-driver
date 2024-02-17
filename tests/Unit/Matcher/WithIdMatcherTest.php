<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithIdMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithIdMatcher::class)]
class WithIdMatcherTest extends BaseUnitTestCase
{
    public function testWithIdToString(): void
    {
        // Arrange
        $matcher = new WithIdMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('withId(mock)', $result);
    }
}
