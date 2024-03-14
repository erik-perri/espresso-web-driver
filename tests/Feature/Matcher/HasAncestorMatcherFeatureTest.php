<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\HasAncestorMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasAncestor;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(HasAncestorMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasAncestor')]
class HasAncestorMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsDistantAncestor(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-ancestor.html')
            ->onElement(allOf(
                withText('Descendant'),
                hasAncestor(withClass('ancestor-a')),
            ))
            ->check(matches(withClass('descendant-a')));
    }

    public function testNegatesBasedOnAncestor(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-ancestor.html')
            ->onElement(allOf(
                withText('Descendant'),
                not(hasAncestor(withClass('ancestor-a'))),
            ))
            ->check(matches(withClass('descendant-b')));
    }
}
