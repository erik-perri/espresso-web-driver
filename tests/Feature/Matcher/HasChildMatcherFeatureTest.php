<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\HasChildMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\hasChild;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\matchesAll;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(HasChildMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasChild')]
class HasChildMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsChildren(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-child.html');

        $espresso->onElement(hasChild(withText('Mock A')))
            ->check(matches(withClass('mock-a')));

        $espresso->onElement(hasChild(withText('Mock B')))
            ->check(matches(withClass('mock-b-child')));
    }

    public function testFindsChildrenUsingNegation(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/has-child.html')
            ->onElement(matchesAll(hasChild(withText('Mock C')), not(hasChild(withText('Mock D')))))
            ->check(matches(withClass('mock-c')));
    }
}
