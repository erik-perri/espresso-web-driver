<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Action;

use EspressoWebDriver\Action\TypeTextAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\focus;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\typeText;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withValue;

#[CoversClass(TypeTextAction::class)]
#[CoversFunction('EspressoWebDriver\typeText')]
class TypeTextActionFeatureTest extends BaseFeatureTestCase
{
    public function testTypesTextInTextInputs(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/actions/type-text.html')
            ->onElement(withId('test-a'))
            ->perform(typeText('Value A'))
            ->check(matches(withValue('Value A')));
    }

    public function testTypesTextInTextareaInputs(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/actions/type-text.html')
            ->onElement(withId('test-b'))
            ->perform(typeText("Value B\nWith new line"))
            ->check(matches(withValue("Value B\nWith new line")));
    }

    public function testTypesTextInSelectInputs(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso->navigateTo('/actions/type-text.html')
            ->onElement(withId('test-c'))
            ->perform(focus(), typeText('Value C'))
            ->check(matches(withValue('Value C')));
    }
}
