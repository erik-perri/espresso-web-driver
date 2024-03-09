<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithLabelMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\doesNotExist;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withLabel;
use function EspressoWebDriver\withTagName;

#[CoversClass(WithLabelMatcher::class)]
#[CoversFunction('EspressoWebDriver\withLabel')]
class WithLabelMatcherFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('labelTextProvider')]
    public function testMatchesLabelsWithText(string $labelText, string $expectedId): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-label.html')
            ->onElement(withLabel($labelText))
            ->check(matches(withId($expectedId)));
    }

    /**
     * @return array<string, array{labelText: string, expectedId: string}>
     */
    public static function labelTextProvider(): array
    {
        return [
            'explicit' => [
                'labelText' => 'Explicit',
                'expectedId' => 'explicit',
            ],
            'implicit' => [
                'labelText' => 'Implicit',
                'expectedId' => 'implicit',
            ],
            'invalid implicit' => [
                'labelText' => 'Invalid implicit',
                'expectedId' => 'implicit_invalid_first',
            ],
            'valid implicit' => [
                'labelText' => 'Valid implicit',
                'expectedId' => 'implicit_valid_second',
            ],
            'outside' => [
                'labelText' => 'Outside',
                'expectedId' => 'outside',
            ],
            'double quotes' => [
                'labelText' => 'Double "quotes"',
                'expectedId' => 'double-quotes',
            ],
            'single quotes' => [
                'labelText' => "Single 'quotes'",
                'expectedId' => 'single-quotes',
            ],
        ];
    }

    public function testReturnsNoResultsForEmptyLabels(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-label.html')
            ->onElement(withLabel('Empty implicit'))
            ->check(doesNotExist());
    }

    public function testReturnsNoResultsForLabelsWithUnavailableIds(): void
    {
        // Arrange
        $containedEspresso = $this->espresso()
            ->inContainer(withClass('label-container'));

        // Act and Assert
        $containedEspresso->navigateTo('/matchers/with-label.html')
            ->onElement(withLabel('Outside'))
            ->check(doesNotExist());
    }

    public function testMatchesNegativeResults(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-label.html')
            ->onElement(allOf(not(withLabel('Explicit')), withTagName('select')))
            ->check(matches(withId('implicit')));
    }
}
