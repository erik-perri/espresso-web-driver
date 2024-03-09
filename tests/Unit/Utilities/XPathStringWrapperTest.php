<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\XPathStringWrapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(XPathStringWrapper::class)]
class XPathStringWrapperTest extends BaseUnitTestCase
{
    #[DataProvider('wrappingProvider')]
    public function testWrapsAsExpected(string $input, string $expected): void
    {
        // Arrange
        $wrapper = new XPathStringWrapper;

        // Act
        $result = $wrapper->wrap($input);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{input: string, expected: string}>
     */
    public static function wrappingProvider(): array
    {
        return [
            'text with no quotes' => [
                'input' => 'Hello World',
                'expected' => "'Hello World'",
            ],
            'text with double quotes' => [
                'input' => 'Hello "World"',
                'expected' => "'Hello \"World\"'",
            ],
            'text with single quotes' => [
                'input' => "Hello 'World'",
                'expected' => '"Hello \'World\'"',
            ],
            'text with both quotes' => [
                'input' => "Hello \"World\" and 'Universe'",
                'expected' => "concat('Hello \"World\" and ', \"'Universe'\")",
            ],
            'text with both quotes chained' => [
                'input' => "Hello \"World\" and 'Universe' and \"Galaxy\"",
                'expected' => "concat('Hello \"World\" and ', \"'Universe' and \", '\"Galaxy\"')",
            ],
        ];
    }
}
