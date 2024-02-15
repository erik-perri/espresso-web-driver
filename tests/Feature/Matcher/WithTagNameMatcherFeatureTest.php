<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\WithTagNameMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(WithTagNameMatcher::class)]
#[CoversFunction('EspressoWebDriver\withTagName')]
class WithTagNameMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesTagNameWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-tag-name.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTagName('b'))
            ->check(matches(withText('Bold')));

        $espresso->onElement(withTagName('p'))
            ->check(matches(withText('Paragraph')));
    }

    public function testMatchesTagNameNegativeWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-tag-name.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->inContainer(withTagName('table'))
            ->onElement(allOf(not(withTagName('col')), not(withTagName('table'))))
            ->check(matches(withTagName('colgroup')));
    }

    public function testMatchesFromContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-tag-name.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTagName('html'))
            ->check(matches(withTagName('html')));
    }

    public function testMatchesNegativeFromContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/with-tag-name.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(withTagName('html'))
            ->check(matches(not(withTagName('body'))));
    }
}
