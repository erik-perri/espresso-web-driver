<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Core\PhpunitReporter;
use EspressoWebDriver\Matcher\WithTextContainingMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withDriver;
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

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTextContaining('Mock A'))
            ->check(matches(isDisplayed()));
    }

    public function testMatchesContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options)
            ->inContainer(withTextContaining('Mock A'));

        // Act and Assert
        $espresso->onElement(withTextContaining('Mock A'))
            ->check(matches(isDisplayed()));
    }

    public function testMatchesWhenCaseIsWrong(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTextContaining('MOCK A'))
            ->check(matches(isDisplayed()));
    }

    public function testDoesMatchesWithLeadingSpaces(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTextContaining('Mock B'))
            ->check(matches(isDisplayed()));
    }

    public function testMatchesSubstrings(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTextContaining('Mock C'))
            ->check(matches(isDisplayed()));
    }
}