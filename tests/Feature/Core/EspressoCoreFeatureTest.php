<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Core;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withDriver;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withText;

class EspressoCoreFeatureTest extends BaseFeatureTestCase
{
    public function testConstrainsToRequestedContainer(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('core/in-container.html'));

        $espresso = withDriver($driver, new EspressoOptions(waitTimeoutInSeconds: 1));

        // Contain the instance to an individual row, then click a matcher that would match many rows if not contained
        $containedEspresso = $espresso
            ->inContainer(allOf(withClass('item'), hasDescendant(withText('Item 2'))));

        // Act
        $containedEspresso
            ->onElement(withTagName('a'))
            ->perform(click());

        // Assert
        $this->assertStringEndsWith('index.html?2', $driver->getCurrentURL());
    }
}
