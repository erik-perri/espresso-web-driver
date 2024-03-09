<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Matcher\AllOfMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\doesNotExist;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(AllOfMatcher::class)]
#[CoversFunction('EspressoWebDriver\AllOf')]
class AllOfMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testChecksForAllMatchProvided(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/all-of.html')
            ->onElement(allOf(
                withClass('row'),
                not(withClass('deleted')),
                hasDescendant(withText('Processed')),
            ))
            ->check(matches(withClass('b')));
    }

    public function testFindsNothingWhenNoMatchersAreProvided(): void
    {
        // Arrange
        $espresso = $this->espresso();

        /**
         * @var MatcherInterface[] $mockMatchers
         */
        $mockMatchers = [];

        // Act and Assert
        $espresso->navigateTo('/matchers/all-of.html')
            ->onElement(allOf(...$mockMatchers))
            ->check(doesNotExist());
    }

    public function testNegatesAsExpected(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/matchers/all-of.html')
            ->onElement(allOf(
                withClass('row'),
                not(withClass('a')),
                not(allOf(
                    hasDescendant(withText('Processed')),
                    withClass('deleted'),
                )),
            ))
            ->check(matches(withClass('b')));
    }
}
