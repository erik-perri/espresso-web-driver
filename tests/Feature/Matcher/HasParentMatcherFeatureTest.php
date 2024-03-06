<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\HasParentMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\hasParent;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(HasParentMatcher::class)]
#[CoversFunction('EspressoWebDriver\hasParent')]
class HasParentMatcherFeatureTest extends BaseFeatureTestCase
{
    public function testFindsParents(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-child.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(hasParent(withClass('mock-a')))
            ->check(matches(withText('Mock A')));

        $espresso->onElement(hasParent(withClass('mock-b-child')))
            ->check(matches(withText('Mock B')));
    }

    public function testFindsParentsUsingNegation(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-child.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso->onElement(allOf(hasParent(withClass('mock-ambiguous')), not(hasParent(withClass('mock-d')))))
            ->check(matches(withText('Mock C')));
    }
}
