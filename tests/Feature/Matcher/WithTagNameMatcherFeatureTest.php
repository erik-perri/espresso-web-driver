<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithTagNameMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(WithTagNameMatcher::class)]
#[CoversFunction('EspressoWebDriver\withTagName')]
class WithTagNameMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesTagNameWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-tag-name.html');

        $espresso->onElement(withTagName('b'))
            ->check(matches(withText('Bold')));

        $espresso->onElement(withTagName('p'))
            ->check(matches(withText('Paragraph')));
    }

    public function testMatchesTagNameNegativeWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-tag-name.html')
            ->inContainer(withTagName('table'))
            ->onElement(allOf(not(withTagName('col')), not(withTagName('table'))))
            ->check(matches(withTagName('colgroup')));
    }

    public function testMatchesFromContainer(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-tag-name.html')
            ->onElement(withTagName('html'))
            ->check(matches(withTagName('html')));
    }

    public function testMatchesNegativeFromContainer(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-tag-name.html')
            ->onElement(withTagName('html'))
            ->check(matches(not(withTagName('body'))));
    }
}
