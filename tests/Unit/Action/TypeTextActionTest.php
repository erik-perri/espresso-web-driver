<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Action;

use EspressoWebDriver\Action\TypeTextAction;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TypeTextAction::class)]
class TypeTextActionTest extends BaseUnitTestCase
{
    public function testTypesProvidedKeys(): void
    {
        // Arrange
        $mockContainer = $this->createMock(WebDriverElement::class);
        $mockContainer
            ->expects($this->once())
            ->method('sendKeys')
            ->with('Mock Keys');

        $action = new TypeTextAction('Mock Keys');

        // Act
        $result = $action->perform($mockContainer);

        // Assert
        $this->assertTrue($result);
    }

    public function testTypeTextToString(): void
    {
        // Arrange
        $assertion = new TypeTextAction('Mock Keys');

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('typeText("Mock Keys")', $result);
    }
}
