<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\IsEnabledMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\isEnabled;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;
use function EspressoWebDriver\withValue;

#[CoversClass(IsEnabledMatcher::class)]
#[CoversFunction('EspressoWebDriver\isEnabled')]
class IsEnabledMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testIsEnabledWorksOnButtons(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-enabled.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('button'), isEnabled()))
            ->check(matches(withText('Enabled')));
        $espresso->onElement(allOf(withTagName('button'), not(isEnabled())))
            ->check(matches(withText('Disabled')));
    }

    public function testIsEnabledWorksOnFieldSets(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-enabled.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('fieldset'), isEnabled()))
            ->check(matches(hasDescendant(withText('Enabled'))));
        $espresso->onElement(allOf(withTagName('fieldset'), not(isEnabled())))
            ->check(matches(hasDescendant(withText('Disabled'))));
    }

    public function testIsEnabledWorksOnInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-enabled.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('input'), isEnabled()))
            ->check(matches(withValue('Enabled')));
        $espresso->onElement(allOf(withTagName('input'), not(isEnabled())))
            ->check(matches(withValue('Disabled')));
    }

    public function testIsEnabledWorksOnSelectsAndOptions(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-enabled.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('select'), isEnabled()))
            ->check(matches(withClass('enabled')));
        $espresso->onElement(allOf(withTagName('select'), not(isEnabled())))
            ->check(matches(withClass('disabled')));

        $espresso->onElement(allOf(withTagName('optgroup'), isEnabled()))
            ->check(matches(hasDescendant(withText('Enabled'))));
        $espresso->inContainer(allOf(withTagName('optgroup'), not(isEnabled())))
            ->onElement(allOf(withTagName('optgroup'), not(isEnabled())))
            ->check(matches(hasDescendant(withText('Disabled'))));

        $espresso->onElement(allOf(withClass('option'), isEnabled()))
            ->check(matches(withText('Enabled')));
        $espresso->onElement(allOf(withClass('option'), not(isEnabled())))
            ->check(matches(withText('Disabled')));
    }

    public function testIsEnabledWorksOnTextAreas(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-enabled.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withTagName('textarea'), isEnabled()))
            ->check(matches(withValue('Enabled')));
        $espresso->onElement(allOf(withTagName('textarea'), not(isEnabled())))
            ->check(matches(withValue('Disabled')));
    }
}
