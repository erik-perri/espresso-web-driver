<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\IsCheckedMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isChecked;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withLabel;

#[CoversClass(IsCheckedMatcher::class)]
#[CoversFunction('EspressoWebDriver\isChecked')]
class IsCheckedMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesDefaultCheckedState(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-checked.html');

        $espresso->onElement(withLabel('Checked'))
            ->check(matches(isChecked()));

        $espresso->onElement(withLabel('Unchecked'))
            ->check(matches(not(isChecked())));
    }

    public function testMatchesChangedState(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/is-checked.html');

        $espresso->onElement(withLabel('Unchecked'))
            ->check(matches(not(isChecked())));

        $espresso->onElement(withLabel('Unchecked'))
            ->perform(click());

        $espresso->onElement(withLabel('Unchecked'))
            ->check(matches(isChecked()));
    }
}
