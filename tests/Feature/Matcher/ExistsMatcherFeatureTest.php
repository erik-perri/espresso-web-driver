<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\ExistsMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\exists;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(ExistsMatcher::class)]
#[CoversFunction('EspressoWebDriver\exists')]
class ExistsMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testChecksElementExistence(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/exists.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withText('Mock element'))
            ->check(matches(not(exists())));

        $espresso
            ->onElement(allOf(withTagName('button'), withText('Create element')))
            ->perform(click());

        $espresso
            ->onElement(withText('Mock element'))
            ->check(matches(exists()));
    }
}
