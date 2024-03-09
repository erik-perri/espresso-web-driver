<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\HasSiblingMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasSibling;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(HasSiblingMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasSibling')]
class HasSiblingMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsSibling(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-sibling.html')
            ->onElement(allOf(withTagName('input'), hasSibling(withText('Password'))))
            ->check(matches(withId('password')));
    }

    public function testNegatesSibling(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-sibling.html')
            ->onElement(allOf(withTagName('input'), not(hasSibling(withClass('error')))))
            ->check(matches(withId('password_confirmation')));
    }
}
