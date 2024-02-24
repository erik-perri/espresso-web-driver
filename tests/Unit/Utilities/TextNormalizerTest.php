<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\TextNormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(TextNormalizer::class)]
class TextNormalizerTest extends BaseUnitTestCase
{
    #[DataProvider('textProvider')]
    public function testNormalizeSpace(string $input, string $expected)
    {
        // Arrange
        $normalizer = new TextNormalizer;

        // Act
        $result = $normalizer->normalize($input);

        // Assert
        $this->assertSame($expected, $result);
    }

    public static function textProvider(): array
    {
        return [
            'leading space' => [
                'input' => '  Hello',
                'expected' => 'Hello',
            ],
            'trailing space' => [
                'input' => 'Hello  ',
                'expected' => 'Hello',
            ],
            'leading and trailing space' => [
                'input' => '  Hello  ',
                'expected' => 'Hello',
            ],
            'multiple spaces' => [
                'input' => 'Hello  World',
                'expected' => 'Hello World',
            ],
            'tab characters' => [
                'input' => "Hello\tWorld",
                'expected' => 'Hello World',
            ],
            'mixed whitespace characters' => [
                'input' => " \t Hello \n World \t Hello \r World ",
                'expected' => 'Hello World Hello World',
            ],
            'no extra spaces' => [
                'input' => 'Hello',
                'expected' => 'Hello',
            ],
            'empty string' => [
                'input' => '',
                'expected' => '',
            ],
            'new line at the start' => [
                'input' => "\nHello",
                'expected' => 'Hello',
            ],
            'new line at the end' => [
                'input' => "Hello\n",
                'expected' => 'Hello',
            ],
            'new line in the middle' => [
                'input' => "Hello\nWorld",
                'expected' => 'Hello World',
            ],
            'multiple new lines' => [
                'input' => "Hello\n\nWorld",
                'expected' => 'Hello World',
            ],
        ];
    }
}
