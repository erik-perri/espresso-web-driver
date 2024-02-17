<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithClassMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithClassMatcher::class)]
class WithClassMatcherTest extends BaseUnitTestCase
{
    public function testWithClassToString(): void
    {
        // Arrange
        $matcher = new WithClassMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('withClass(mock)', $result);
    }
}
