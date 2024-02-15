<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SubmitAction::class)]
class SubmitActionTest extends BaseUnitTestCase
{
    public function testSubmitToString(): void
    {
        // Arrange
        $assertion = new SubmitAction();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('submit', $result);
    }
}
