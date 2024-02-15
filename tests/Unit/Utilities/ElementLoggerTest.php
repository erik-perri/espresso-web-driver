<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\ElementLogger;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(ElementLogger::class)]
class ElementLoggerTest extends BaseUnitTestCase
{
    public function testLogsTheTagWhenNoNotableAttributes(): void
    {
        // Arrange
        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->once())
            ->method('getTagName')
            ->willReturn('div');

        // Act
        $elementLog = new ElementLogger($element);

        // Assert
        $this->assertSame('<div>', (string) $elementLog);
    }

    #[DataProvider('notableAttributes')]
    public function testLogsNotableAttributes(string $key, string $value, string $expected): void
    {
        // Arrange
        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->once())
            ->method('getTagName')
            ->willReturn('input');
        $element->method('getAttribute')
            ->willReturnMap([
                [$key, $value],
            ]);

        // Act
        $elementLog = new ElementLogger($element);

        // Assert
        $this->assertSame('<input '.$expected.'>', (string) $elementLog);
    }

    public static function notableAttributes(): array
    {
        return [
            'alt' => [
                'key' => 'alt',
                'value' => 'Mock',
                'expected' => 'alt="Mock"',
            ],
            'class' => [
                'key' => 'class',
                'value' => 'test',
                'expected' => 'class="test"',
            ],
            'disabled' => [
                'key' => 'disabled',
                'value' => 'disabled',
                'expected' => 'disabled="disabled"',
            ],
            'href' => [
                'key' => 'href',
                'value' => 'http://example.com',
                'expected' => 'href="http://example.com"',
            ],
            'id' => [
                'key' => 'id',
                'value' => 'mock',
                'expected' => 'id="mock"',
            ],
            'name' => [
                'key' => 'name',
                'value' => 'mock',
                'expected' => 'name="mock"',
            ],
            'src' => [
                'key' => 'src',
                'value' => 'http://example.com/image.jpg',
                'expected' => 'src="http://example.com/image.jpg"',
            ],
            'title' => [
                'key' => 'title',
                'value' => 'Mock',
                'expected' => 'title="Mock"',
            ],
            'type' => [
                'key' => 'type',
                'value' => 'password',
                'expected' => 'type="password"',
            ],
            'value' => [
                'key' => 'value',
                'value' => '123',
                'expected' => 'value="123"',
            ],
        ];
    }
}
