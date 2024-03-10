<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\CommonAncestorOfMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\commonAncestorOf;
use function EspressoWebDriver\doesNotExist;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(CommonAncestorOfMatcher::class)]
#[CoversFunction('EspressoWebDriver\commonAncestorOf')]
class CommonAncestorOfMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testChecksForCommonAncestor(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/common-ancestor-of.html');

        $espresso->onElement(commonAncestorOf(withText('John Doe'), withText('Processing')))
            ->check(matches(withClass('test-a')));

        $espresso->onElement(commonAncestorOf(withText('John Doe'), withText('Deleted')))
            ->check(matches(hasDescendant(withText('3'))));

        $espresso->onElement(commonAncestorOf(withText('John Doe'), withText('Jane Doe')))
            ->check(matches(withTagName('body')));
    }

    public function testDoesNotReturnIfAllDoNotMatch(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/common-ancestor-of.html')
            ->onElement(commonAncestorOf(withText('John Doe'), withText('What')))
            ->check(doesNotExist());
    }
}
