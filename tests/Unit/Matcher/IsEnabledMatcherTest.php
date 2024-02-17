<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\IsEnabledMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsEnabledMatcher::class)]
class IsEnabledMatcherTest extends BaseUnitTestCase
{
    public function testIsEnabledToString(): void
    {
        // Arrange
        $matcher = new IsEnabledMatcher();

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('isEnabled', $result);
    }
}
