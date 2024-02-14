<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\HasFocusMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasFocusMatcher::class)]
class HasFocusMatcherTest extends BaseUnitTestCase
{
    public function testHasFocusToString(): void
    {
        // Arrange
        $matcher = new HasFocusMatcher();

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('focused', $result);
    }
}
