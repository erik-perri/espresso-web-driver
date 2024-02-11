<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Core\PhpunitReporter;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\WithTextMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withDriver;
use function EspressoWebDriver\withText;

#[CoversClass(WithTextMatcher::class)]
#[CoversFunction('EspressoWebDriver\withText')]
class WithTextMatcherFeatureTest extends BaseFeatureTestCase
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
        $espresso->onElement(withText('Mock A'))
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
            ->inContainer(withText('Mock A'));

        // Act and Assert
        $espresso->onElement(withText('Mock A'))
            ->check(matches(isDisplayed()));
    }

    public function testDoesNotMatchWhenCaseIsWrong(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for text="MOCK A"');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(waitTimeoutInSeconds: 0);

        $espresso = withDriver($driver, $options);

        // Act
        $espresso->onElement(withText('MOCK A'));
    }

    public function testDoesNotMatchWithLeadingSpaces(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for text="Mock B"');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(waitTimeoutInSeconds: 0);

        $espresso = withDriver($driver, $options);

        // Act
        $espresso->onElement(withText('Mock B'));
    }

    public function testDoesNotMatchSubstrings(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for text="Mock C"');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(waitTimeoutInSeconds: 0);

        $espresso = withDriver($driver, $options);

        // Act
        $espresso->onElement(withText('Mock C'));
    }
}
