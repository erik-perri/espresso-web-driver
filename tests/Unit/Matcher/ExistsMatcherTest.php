<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\ExistsMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ExistsMatcher::class)]
class ExistsMatcherTest extends BaseUnitTestCase
{
    public function testExistsAssertionToString(): void
    {
        // Arrange
        $assertion = new ExistsMatcher();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('exists', $result);
    }
}
