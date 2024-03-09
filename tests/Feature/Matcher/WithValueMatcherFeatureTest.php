<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithValueMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;
use function EspressoWebDriver\withValue;

#[CoversClass(WithValueMatcher::class)]
#[CoversFunction('EspressoWebDriver\withValue')]
class WithValueMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesButtonsExactly(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue('button-value'))
            ->check(matches(withText('Button')));
    }

    public function testMatchesNegatedButtonsExactly(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(allOf(withTagName('button'), not(withValue('button-value'))))
            ->check(matches(withText('Button without longer value')));
    }

    public function testMatchesOptionsExactly(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue('option-value-1'))
            ->check(matches(withText('Option 1')));
    }

    public function testMatchesLists(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue('5'))
            ->check(matches(withText('List')));
    }

    public function testMatchesNegativeLists(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(allOf(withTagName('li'), not(withValue('5'))))
            ->check(matches(withText('List starting at 6')));
    }

    public function testMatchesMetersExactly(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue('0.5'))
            ->check(matches(withText('50%')));
    }

    public function testMatchesProgressExactly(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue('0.6'))
            ->check(matches(withText('60%')));
    }

    public function testMatchesContainerValues(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue('0.6'))
            ->check(matches(withValue('0.6')))
            ->check(matches(not(withValue('0.5'))));
    }

    #[DataProvider('quoteValueProvider')]
    public function testMatchesValuesWithQuotes(string $value, string $expectedId): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-value.html')
            ->onElement(withValue($value))
            ->check(matches(withId($expectedId)));
    }

    /**
     * @return array<string, array{value: string, expectedId: string}>
     */
    public static function quoteValueProvider(): array
    {
        return [
            'double quotes' => [
                'value' => 'Double "quotes"',
                'expectedId' => 'double-quotes',
            ],
            'single quotes' => [
                'value' => "Single 'quotes'",
                'expectedId' => 'single-quotes',
            ],
        ];
    }
}
