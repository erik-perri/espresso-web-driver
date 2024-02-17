<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\IsCheckedMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isChecked;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withLabel;

#[CoversClass(IsCheckedMatcher::class)]
#[CoversFunction('EspressoWebDriver\isChecked')]
class IsCheckedMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesDefaultCheckedState(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-checked.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Checked'))
            ->check(matches(isChecked()));
        $espresso->onElement(withLabel('Unchecked'))
            ->check(matches(not(isChecked())));
    }

    public function testMatchesChangedState(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/is-checked.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withLabel('Unchecked'))
            ->check(matches(not(isChecked())));

        $espresso->onElement(withLabel('Unchecked'))
            ->perform(click());

        $espresso->onElement(withLabel('Unchecked'))
            ->check(matches(isChecked()));
    }
}
