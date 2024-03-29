<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\HasDescendantMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\matchesAll;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(HasDescendantMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasDescendant')]
class HasDescendantMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsDeepDescendant(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-descendant.html')
            ->onElement(matchesAll(withClass('test'), hasDescendant(withText('Mock B'))))
            ->check(matches(withTagName('div')));
    }

    public function testNegatesBasedOnDescendant(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-descendant.html')
            ->onElement(matchesAll(
                withClass('test'),
                hasDescendant(withText('Mock C')),
                not(hasDescendant(withText('Mock D'))),
            ))
            ->check(matches(withId('without-mock-d')));
    }
}
