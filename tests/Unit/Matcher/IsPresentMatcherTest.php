<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\IsPresentMatcher;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsPresentMatcher::class)]
class IsPresentMatcherTest extends BaseUnitTestCase
{
    public function testPresentMatcherToString(): void
    {
        // Arrange
        $assertion = new IsPresentMatcher();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('isPresent', $result);
    }
}
