<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\WithLabelMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\doesNotExist;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
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
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel($labelText))
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
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Empty implicit'))
            ->check(doesNotExist());
    }

    public function testReturnsNoResultsForLabelsWithUnavailableIds(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options)
            ->inContainer(withClass('label-container'));

        // Act and Assert
        $espresso->onElement(withLabel('Outside'))
            ->check(doesNotExist());
    }

    public function testMatchesNegativeResults(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(not(withLabel('Explicit')), withTagName('select')))
            ->check(matches(withId('implicit')));
    }
}
