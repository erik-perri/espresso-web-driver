<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\IsCheckedMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsCheckedMatcher::class)]
class IsCheckedMatcherTest extends BaseUnitTestCase
{
    public function testIsCheckedToString(): void
    {
        // Arrange
        $matcher = new IsCheckedMatcher;

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('isChecked', $result);
    }
}
