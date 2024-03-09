<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\AnyOfMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\anyOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(AnyOfMatcher::class)]
#[CoversFunction('EspressoWebDriver\anyOf')]
class AnyOfMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testChecksForAnyMatchProvided(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/any-of.html');

        $espresso->onElement(allOf(withClass('status'), anyOf(withText('Processing'), withText('Done'))))
            ->check(matches(withText('Processing')))
            ->perform(click());

        $espresso->onElement(allOf(withClass('status'), anyOf(withText('Processing'), withText('Done'))))
            ->check(matches(withText('Done')));
    }

    public function testNegatesAsExpected(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/any-of.html')
            ->onElement(allOf(withClass('status'), not(anyOf(withText('Processing'), withText('Done')))))
            ->check(matches(withText('Deleted')));
    }
}
