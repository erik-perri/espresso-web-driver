<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\ClearTextAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\clearText;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\typeText;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withValue;

#[CoversClass(ClearTextAction::class)]
#[CoversFunction('EspressoWebDriver\clearText')]
class ClearTextActionFeatureTest extends BaseFeatureTestCase
{
    public function testClearsTextInTextInputs(): void
    {
        // Arrange
        $espresso = $this->espresso(new EspressoOptions(assertionReporter: new PhpunitReporter));

        // Act and Assert
        $espresso
            ->goTo($this->mockStaticUrl('actions/clear-text.html'))
            ->onElement(withId('test-a'))
            ->perform(typeText('Value A'))
            ->check(matches(withValue('Value A')))
            ->perform(clearText())
            ->check(matches(withValue('')));
    }

    public function testClearsTextInTextareaInputs(): void
    {
        // Arrange
        $espresso = $this->espresso(new EspressoOptions(assertionReporter: new PhpunitReporter));

        // Act and Assert
        $espresso
            ->goTo($this->mockStaticUrl('actions/clear-text.html'))
            ->onElement(withId('test-b'))
            ->perform(clearText())
            ->check(matches(withValue('')))
            ->perform(typeText('Value B\nWith new line'))
            ->check(matches(withValue('Value B\nWith new line')))
            ->perform(clearText())
            ->check(matches(withValue('')));
    }
}
