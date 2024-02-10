<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
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
class HasDescendantMatcherTest extends BaseFeatureTestCase
{
    public function testFindsDeepDescendant(): void
    {
        $driver = $this->driver()->get($this->mockStaticUrl('has-descendants.html'));

        $espresso = withDriver($driver, new EspressoOptions(waitTimeoutInSeconds: 1));

        $espresso->onElement(allOf(withClass('test'), hasDescendant(withText('Mock B'))))
            ->check(matches(withTagName('div')));

        // TODO Wire up check and exceptions to Phpunit
        $this->assertTrue(true);
    }
}
