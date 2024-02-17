<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\WithLabelMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WithLabelMatcher::class)]
class WithLabelMatcherTest extends BaseUnitTestCase
{
    public function testWithLabelToString(): void
    {
        // Arrange
        $matcher = new WithLabelMatcher('mock');

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('withLabel(mock)', $result);
    }
}
