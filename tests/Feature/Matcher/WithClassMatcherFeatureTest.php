<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\WithClassMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(WithClassMatcher::class)]
#[CoversFunction('EspressoWebDriver\withClass')]
class WithClassMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesClassWithoutSelectingMoreSpecificText(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-class.html')
            ->onElement(withClass('test'))
            ->check(matches(withText('Test')));
    }

    public function testMatchesFromContainer(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-class.html')
            ->onElement(withClass('html'))
            ->check(matches(withTagName('html')));
    }

    public function testMatchesNegativeFromContainer(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/with-class.html')
            ->onElement(withClass('html'))
            ->check(matches(not(withClass('not-html'))));
    }
}
