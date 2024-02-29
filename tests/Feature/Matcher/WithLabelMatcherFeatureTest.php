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
    public function testMatchesExplicitLabelledElement(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Explicit'))
            ->check(matches(withId('explicit')));
    }

    public function testMatchesImplicitLabelledElement(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Implicit'))
            ->check(matches(withId('implicit')));
    }

    public function testMatchesOnlyFirstElementOnIncorrectlyImplicitlyLabelledElements(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Invalid implicit'))
            ->check(matches(withId('implicit_invalid_first')));
    }

    public function testMatchesCorrectElementOnImplicitlyLabelledElementsWithHidden(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Valid implicit'))
            ->check(matches(withId('implicit_valid_second')));
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

    public function testReturnsResultsFromOutsideTheLabelsParent(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-label.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Outside'))
            ->check(matches(withId('outside')));
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
