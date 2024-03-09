<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithTextContainingMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\exists;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;
use function EspressoWebDriver\withTextContaining;

#[CoversClass(WithTextContainingMatcher::class)]
#[CoversFunction('EspressoWebDriver\withTextContaining')]
class WithTextContainingMatcherFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('exactMatchProvider')]
    public function testMatchesExactly(string $match, string $expectedClass): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-text.html')
            ->onElement(withTextContaining($match))
            ->check(matches(withClass($expectedClass)));
    }

    /**
     * @return array<string, array{match: string, expectedClass: string}>
     */
    public static function exactMatchProvider(): array
    {
        return [
            'normal text' => [
                'match' => 'ock A',
                'expectedClass' => 'a',
            ],
            'quoted text using double quotes' => [
                'match' => '"quoted" text',
                'expectedClass' => 'e',
            ],
            'quoted text using single quotes' => [
                'match' => "'quoted' text",
                'expectedClass' => 'f',
            ],
        ];
    }

    public function testMatchesContainer(): void
    {
        // Arrange
        $containedEspresso = $this->espresso()
            ->inContainer(allOf(withTagName('li'), withTextContaining('Mock A')));

        // Act and Assert
        $containedEspresso->navigateTo('/matchers/with-text.html')
            ->onElement(withTextContaining('Mock A'))
            ->check(exists());
    }

    public function testMatchesNegativeContainer(): void
    {
        // Arrange
        $containedEspresso = $this->espresso()
            ->inContainer(allOf(withTagName('li'), withTextContaining('Another D')));

        // Act and Assert
        $containedEspresso->navigateTo('/matchers/with-text.html')
            ->onElement(not(withTextContaining('Mock')))
            ->check(exists());
    }

    public function testMatchesWhenCaseIsWrong(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-text.html')
            ->onElement(allOf(withTagName('li'), withTextContaining('MOCK A')))
            ->check(exists());
    }

    public function testDoesMatchesWithLeadingSpaces(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-text.html')
            ->onElement(allOf(withTagName('li'), withTextContaining('Mock B')))
            ->check(exists());
    }

    public function testMatchesSubstrings(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-text.html')
            ->onElement(allOf(withTagName('li'), withTextContaining('Mock C')))
            ->check(exists());
    }

    public function testMatchesNegativeSubstrings(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-text.html')
            ->onElement(allOf(withTagName('li'), not(withTextContaining('mock'))))
            ->check(matches(withText('Another D')));
    }
}
