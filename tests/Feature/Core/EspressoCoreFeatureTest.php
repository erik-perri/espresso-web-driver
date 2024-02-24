<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Core;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Processor\MatchProcessor;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

#[CoversClass(EspressoContext::class)]
#[CoversClass(EspressoCore::class)]
#[CoversClass(EspressoOptions::class)]
#[CoversClass(MatchProcessor::class)]
#[CoversFunction('EspressoWebDriver\matches')]
#[CoversFunction('EspressoWebDriver\usingDriver')]
class EspressoCoreFeatureTest extends BaseFeatureTestCase
{
    public function testConstrainsToRequestedContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('core/in-container.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Contain the instance to an individual row, then click a matcher that would match many rows if not contained
        $containedEspresso = $espresso
            ->inContainer(allOf(withClass('item'), hasDescendant(withText('Item 2'))));

        // Act
        $containedEspresso
            ->onElement(withTagName('a'))
            ->check(matches(withText('Edit')))
            ->perform(click());

        // Assert
        $this->assertStringEndsWith('index.html?2', $driver->getCurrentURL());
    }
}
