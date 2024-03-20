<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\HasParentMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\hasParent;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\matchesAll;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(HasParentMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasParent')]
class HasParentMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsParents(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-child.html');

        $espresso->onElement(hasParent(withClass('mock-a')))
            ->check(matches(withText('Mock A')));

        $espresso->onElement(hasParent(withClass('mock-b-child')))
            ->check(matches(withText('Mock B')));
    }

    public function testFindsParentsUsingNegation(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-child.html')
            ->onElement(matchesAll(hasParent(withClass('mock-ambiguous')), not(hasParent(withClass('mock-d')))))
            ->check(matches(withText('Mock C')));
    }
}
