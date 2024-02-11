<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Assertions;

use EspressoWebDriver\Assertion\ExistsAssertion;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\exists;
use function EspressoWebDriver\not;
use function EspressoWebDriver\withDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(ExistsAssertion::class)]
#[CoversFunction('EspressoWebDriver\exists')]
class ExistsAssertionFeatureTest extends BaseFeatureTestCase
{
    public function testChecksIfElementExists(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('assertions/exists.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(allOf(withTagName('button'), withText('Create element')))
            ->check(exists());
    }

    public function testChecksIfElementDoNotExist(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('assertions/exists.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withText('Mock element'))
            ->check(not(exists()));

        $espresso
            ->onElement(allOf(withTagName('button'), withText('Create element')))
            ->perform(click());

        $espresso
            ->onElement(withText('Mock element'))
            ->check(exists());
    }
}
