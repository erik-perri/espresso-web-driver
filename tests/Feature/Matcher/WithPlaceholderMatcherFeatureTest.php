<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithPlaceholderMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withPlaceholder;
use function EspressoWebDriver\withTagName;

#[CoversClass(WithPlaceholderMatcher::class)]
#[CoversFunction('EspressoWebDriver\withPlaceholder')]
class WithPlaceholderMatcherFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('placeholderProvider')]
    public function testMatchesElementsAsExpected(string $placeholder, string $tagName): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-placeholder.html')
            ->onElement(withPlaceholder($placeholder))
            ->check(matches(withTagName($tagName)));
    }

    /**
     * @return array<string, array{placeholder: string, tagName: string}>
     */
    public static function placeholderProvider(): array
    {
        return [
            'input' => [
                'placeholder' => 'Placeholder A',
                'tagName' => 'input',
            ],
            'textarea' => [
                'placeholder' => 'Placeholder B',
                'tagName' => 'textarea',
            ],
        ];
    }

    #[DataProvider('placeholderNegativeProvider')]
    public function testMatchesElementsUsingNegativeAsExpected(string $placeholder, string $tagName): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-placeholder.html')
            ->onElement(allOf(withClass('input'), not(withPlaceholder($placeholder))))
            ->check(matches(withTagName($tagName)));
    }

    /**
     * @return array<string, array{placeholder: string, tagName: string}>
     */
    public static function placeholderNegativeProvider(): array
    {
        return [
            'input' => [
                'placeholder' => 'Placeholder A',
                'tagName' => 'textarea',
            ],
            'textarea' => [
                'placeholder' => 'Placeholder B',
                'tagName' => 'input',
            ],
        ];
    }
}
