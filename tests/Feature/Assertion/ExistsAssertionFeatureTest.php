<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Assertion;

use EspressoWebDriver\Assertion\DoesNotExistAssertion;
use EspressoWebDriver\Assertion\ExistsAssertion;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\doesNotExist;
use function EspressoWebDriver\exists;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(DoesNotExistAssertion::class)]
#[CoversClass(ExistsAssertion::class)]
#[CoversFunction('EspressoWebDriver\doesNotExist')]
#[CoversFunction('EspressoWebDriver\exists')]
class ExistsAssertionFeatureTest extends BaseFeatureTestCase
{
    public function testChecksExistence(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('assertions/exists.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withText('Mock element'))
            ->check(doesNotExist());

        $espresso
            ->onElement(allOf(withTagName('button'), withText('Create element')))
            ->perform(click());

        $espresso
            ->onElement(withText('Mock element'))
            ->check(exists());
    }

    public function testNotWorksWithOnlyOneResult(): void
    {
        // Expectations
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('Failed to assert doesNotExist');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('assertions/exists.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(allOf(withTagName('button'), withText('Create element')))
            ->perform(click());

        $espresso
            ->onElement(withText('Mock element'))
            ->check(doesNotExist());
    }
}
