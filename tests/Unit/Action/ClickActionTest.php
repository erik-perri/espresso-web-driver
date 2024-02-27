<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClickAction;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ClickAction::class)]
class ClickActionTest extends BaseUnitTestCase
{
    public function testClickToString(): void
    {
        // Arrange
        $action = new ClickAction();

        // Act
        $result = (string) $action;

        // Assert
        $this->assertSame('click', $result);
    }
}
