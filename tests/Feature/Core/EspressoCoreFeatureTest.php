<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Core;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Processor\MatchProcessor;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use EspressoWebDriver\Tests\Utilities\StaticUrlProcessor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\allOf;
use function EspressoWebDriver\click;
use function EspressoWebDriver\hasDescendant;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
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
        $driver = $this->driver();

        $options = new EspressoOptions(
            assertionReporter: new PhpunitReporter,
            urlProcessor: new StaticUrlProcessor,
        );

        $espresso = usingDriver($driver, $options);

        // Contain the instance to an individual row, then click a matcher that would match many rows if not contained
        $containedEspresso = $espresso
            ->inContainer(allOf(withClass('item'), hasDescendant(withText('Item 2'))));

        // Act
        $espresso->navigateTo('/core/in-container.html');

        $containedEspresso
            ->onElement(withTagName('a'))
            ->check(matches(withText('Edit')))
            ->perform(click());

        // Assert
        $espresso->onElement(withId('status'))
            ->check(matches(withText('Edit 2')));
    }
}
