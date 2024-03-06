<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\HasChildMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasChild;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(HasChildMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasDescendant')]
class HasChildMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsChildren(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-child.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(hasChild(withText('Mock A')))
            ->check(matches(withClass('mock-a')));

        $espresso->onElement(hasChild(withText('Mock B')))
            ->check(matches(withClass('mock-b-child')));
    }

    public function testFindsChildrenUsingNegation(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-child.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(hasChild(withText('Mock C')), not(hasChild(withText('Mock D')))))
            ->check(matches(withClass('mock-c')));
    }
}
