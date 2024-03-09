<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\IsEnabledMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\isEnabled;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
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
        $containedEspresso = $this->espresso()
            ->inContainer(withClass('buttons'));

        // Act and Assert
        $containedEspresso->navigateTo('/matchers/is-enabled.html');

        $containedEspresso->onElement(allOf(withTagName('button'), isEnabled()))
            ->check(matches(withText('Enabled')));

        $containedEspresso->onElement(allOf(withTagName('button'), not(isEnabled())))
            ->check(matches(withText('Disabled')));
    }

    public function testIsEnabledWorksOnFieldSets(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-enabled.html');

        $espresso->onElement(allOf(withTagName('fieldset'), isEnabled()))
            ->check(matches(hasDescendant(withText('Enabled'))));

        $espresso->onElement(allOf(withTagName('fieldset'), not(isEnabled())))
            ->check(matches(hasDescendant(withText('Disabled'))));
    }

    public function testIsEnabledWorksOnFieldSetsChildren(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-enabled.html')
            ->onElement(allOf(withTagName('fieldset'), not(isEnabled())))
            ->check(matches(hasDescendant(allOf(withTagName('button'), not(isEnabled())))));
    }

    public function testIsEnabledWorksOnInputs(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-enabled.html');

        $espresso->onElement(allOf(withTagName('input'), isEnabled()))
            ->check(matches(withValue('Enabled')));

        $espresso->onElement(allOf(withTagName('input'), not(isEnabled())))
            ->check(matches(withValue('Disabled')));
    }

    public function testIsEnabledWorksOnSelectsAndOptions(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-enabled.html');

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
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-enabled.html');

        $espresso->onElement(allOf(withTagName('textarea'), isEnabled()))
            ->check(matches(withValue('Enabled')));
        $espresso->onElement(allOf(withTagName('textarea'), not(isEnabled())))
            ->check(matches(withValue('Disabled')));
    }
}
