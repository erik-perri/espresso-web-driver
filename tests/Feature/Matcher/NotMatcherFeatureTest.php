<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\NotMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\matchesAll;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(NotMatcher::class)]
#[CoversFunction('EspressoWebDriver\not')]
class NotMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testMatchesElements(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/not.html')
            ->onElement(matchesAll(withClass('test'), not(withTagName('div'))))
            ->check(matches(withText('Span')));
    }

    public function testMatchesElementsUsingTheInverseOfPositiveWhenUsingMatchersWithoutNegativeSupport(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/not.html')
            ->onElement(matchesAll(withClass('parent'), not(hasDescendant(withText('Child A')))))
            ->check(matches(withClass('b')));
    }
}
