<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\AnyOfMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\anyOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(AnyOfMatcher::class)]
#[CoversFunction('EspressoWebDriver\anyOf')]
class AnyOfMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testChecksForAnyMatchProvided(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/any-of.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(allOf(withClass('status'), anyOf(withText('Processing'), withText('Done'))))
            ->check(matches(withText('Processing')))
            ->perform(click());

        $espresso
            ->onElement(allOf(withClass('status'), anyOf(withText('Processing'), withText('Done'))))
            ->check(matches(withText('Done')));
    }
}
