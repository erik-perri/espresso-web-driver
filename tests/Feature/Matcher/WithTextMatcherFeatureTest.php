<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\WithTextMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\exists;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(WithTextMatcher::class)]
#[CoversFunction('EspressoWebDriver\withText')]
class WithTextMatcherFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('exactMatchProvider')]
    public function testMatchesExactly(string $match, string $expectedClass): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withText($match))
            ->check(matches(withClass($expectedClass)));
    }

    /**
     * @return array<string, array{match: string, expectedClass: string}>
     */
    public static function exactMatchProvider(): array
    {
        return [
            'normal text' => [
                'match' => 'Mock A',
                'expectedClass' => 'a',
            ],
            'quoted text using double quotes' => [
                'match' => 'Some mock "quoted" text.',
                'expectedClass' => 'e',
            ],
            'quoted text using single quotes' => [
                'match' => "Some mock 'quoted' text.",
                'expectedClass' => 'f',
            ],
        ];
    }

    public function testMatchesNegativeExactly(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(
            withTagName('li'),
            not(withText('Mock A')),
            not(withText('Mock B')),
            not(withText('Another D')),
            not(withText('Some mock "quoted" text.')),
            not(withText("Some mock 'quoted' text.")),
        ))
            ->check(matches(withClass('c')));
    }

    public function testMatchesContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options)
            ->inContainer(withText('Mock A'));

        // Act and Assert
        $espresso->onElement(withText('Mock A'))
            ->check(exists());
    }

    public function testMatchesNegativeContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options)
            ->inContainer(withText('Another D'));

        // Act and Assert
        $espresso->onElement(not(withText('Mock A')))
            ->check(exists());
    }

    public function testDoesNotMatchWhenCaseIsWrong(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action click, no element found for withText(MOCK A)');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withText('MOCK A'))
            ->perform(click());

        // Assert
        // No assertions, only expectations.
    }

    public function testDoesNotMatchSubstrings(): void
    {
        // Expectations
        $this->expectException(PerformException::class);
        $this->expectExceptionMessage('Failed to perform action click, no element found for withText(Mock C)');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withText('Mock C'))
            ->perform(click());

        // Assert
        // No assertions, only expectations.
    }
}
