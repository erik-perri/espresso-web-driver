<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithIdMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(WithIdMatcher::class)]
#[CoversFunction('EspressoWebDriver\withId')]
class WithIdMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesIdWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-id.html')
            ->onElement(withId('test'))
            ->check(matches(withText('Test')));
    }

    public function testMatchesTagNameNegativeWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-id.html')
            ->onElement(allOf(not(withId('test')), withTagName('span')))
            ->check(matches(withText('Testing')));
    }

    public function testMatchesFromContainer(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-id.html')
            ->onElement(withId('html'))
            ->check(matches(withTagName('html')));
    }

    public function testMatchesNegativeFromContainer(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-id.html')
            ->onElement(withId('html'))
            ->check(matches(not(withId('not-html'))));
    }
}
