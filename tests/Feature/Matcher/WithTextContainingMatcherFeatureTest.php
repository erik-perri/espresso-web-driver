<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\WithTextContainingMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\isPresent;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;
use function EspressoWebDriver\withTextContaining;

#[CoversClass(WithTextContainingMatcher::class)]
#[CoversFunction('EspressoWebDriver\withTextContaining')]
class WithTextContainingMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesExactly(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('li'), withTextContaining('Mock A')))
            ->check(matches(isPresent()));
    }

    public function testMatchesContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options)
            ->inContainer(allOf(withTagName('li'), withTextContaining('Mock A')));

        // Act and Assert
        $espresso->onElement(withTextContaining('Mock A'))
            ->check(matches(isPresent()));
    }

    public function testMatchesNegativeContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options)
            ->inContainer(allOf(withTagName('li'), withTextContaining('Another D')));

        // Act and Assert
        $espresso->onElement(not(withTextContaining('Mock')))
            ->check(matches(isPresent()));
    }

    public function testMatchesWhenCaseIsWrong(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('li'), withTextContaining('MOCK A')))
            ->check(matches(isPresent()));
    }

    public function testDoesMatchesWithLeadingSpaces(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('li'), withTextContaining('Mock B')))
            ->check(matches(isPresent()));
    }

    public function testMatchesSubstrings(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('li'), withTextContaining('Mock C')))
            ->check(matches(isPresent()));
    }

    public function testMatchesNegativeSubstrings(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('li'), not(withTextContaining('mock'))))
            ->check(matches(withText('Another D')));
    }
}
