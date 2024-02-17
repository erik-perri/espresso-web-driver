<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\WithTextMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\isPresent;
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
        $espresso->onElement(withText('Mock A'))
            ->check(matches(withClass('a')));
    }

    public function testMatchesNegativeExactly(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(
            withTagName('li'),
            not(withText('Mock A')),
            not(withText('Mock B')),
            not(withText('Another D')),
        ))
            ->check(matches(withClass('c')));
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
            ->inContainer(withText('Mock A'));

        // Act and Assert
        $espresso->onElement(withText('Mock A'))
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
            ->inContainer(withText('Another D'));

        // Act and Assert
        $espresso->onElement(not(withText('Mock A')))
            ->check(matches(isPresent()));
    }

    public function testDoesNotMatchWhenCaseIsWrong(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for withText(MOCK A)');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(waitTimeoutInSeconds: 0);

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
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for withText(Mock C)');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-text.html'));

        $options = new EspressoOptions(waitTimeoutInSeconds: 0);

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withText('Mock C'))
            ->perform(click());

        // Assert
        // No assertions, only expectations.
    }
}
