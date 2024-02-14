<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SendKeysAction;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SendKeysAction::class)]
class SendKeysActionTest extends BaseUnitTestCase
{
    public function testClickToStringWithKeys(): void
    {
        // Arrange
        $assertion = new SendKeysAction(WebDriverKeys::DELETE, WebDriverKeys::END);

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('sendKeys(DELETE, END)', $result);
    }

    public function testClickToStringWithStrings(): void
    {
        // Arrange
        $assertion = new SendKeysAction('use', 'type', 'text');

        // Act
        $result = (string) $assertion;

        // Assert
        $this->assertSame('sendKeys(use, type, text)', $result);
    }
}
