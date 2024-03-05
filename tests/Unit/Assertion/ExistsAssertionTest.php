<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Assertion;

use EspressoWebDriver\Assertion\ExistsAssertion;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ExistsAssertion::class)]
class ExistsAssertionTest extends BaseUnitTestCase
{
    public function testPresentMatcherToString(): void
    {
        // Arrange
        $matcher = new ExistsAssertion();

        // Act
        $result = (string) $matcher;

        // Assert
        $this->assertSame('exists', $result);
    }
}
