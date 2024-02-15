<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ScrollToAction;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ScrollToAction::class)]
class ScrollToActionTest extends BaseUnitTestCase
{
    public function testScrollToToString(): void
    {
        // Arrange
        $assertion = new ScrollToAction();

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('scrollTo', $result);
    }
}
