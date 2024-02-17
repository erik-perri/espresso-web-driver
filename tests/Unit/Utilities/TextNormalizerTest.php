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
            'leading space' => ['  Hello', 'Hello'],
            'trailing space' => ['Hello  ', 'Hello'],
            'leading and trailing space' => ['  Hello  ', 'Hello'],
            'multiple spaces' => ['Hello  World', 'Hello World'],
            'tab characters' => ["Hello\tWorld", 'Hello World'],
            'new line characters' => ["Hello\nWorld", 'Hello World'],
            'mixed whitespace characters' => [" \t Hello \n World \t ", 'Hello World'],
            'no extra spaces' => ['Hello', 'Hello'],
            'empty string' => ['', ''],
            'new line at the start' => ["\nHello", 'Hello'],
            'new line at the end' => ["Hello\n", 'Hello'],
            'new line in the middle' => ["Hello\nWorld", 'Hello World'],
            'multiple new lines' => ["Hello\n\nWorld", 'Hello World'],
        ];
    }
}
