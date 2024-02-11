<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Core\PhpunitReporter;
use EspressoWebDriver\Matcher\HasDescendantMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(HasDescendantMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasDescendant')]
class HasDescendantMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsDeepDescendant(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-descendant.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = withDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(withClass('test'), hasDescendant(withText('Mock B'))))
            ->check(matches(withTagName('div')));
    }
}