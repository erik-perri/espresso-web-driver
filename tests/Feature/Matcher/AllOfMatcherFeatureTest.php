<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\AllOfMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\isPresent;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(AllOfMatcher::class)]
#[CoversFunction('EspressoWebDriver\AllOf')]
class AllOfMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testChecksForAllMatchProvided(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/all-of.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(allOf(
                withClass('row'),
                not(withClass('deleted')),
                hasDescendant(withText('Processed')),
            ))
            ->check(matches(withClass('a')));
    }

    public function testFindsNothingWhenNoMatchersAreProvided(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/all-of.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(allOf())
            ->check(matches(not(isPresent())));
    }
}
