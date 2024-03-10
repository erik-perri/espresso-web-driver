<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\DragAndDropAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\dragAndDrop;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withText;

#[CoversClass(DragAndDropAction::class)]
#[CoversFunction('EspressoWebDriver\dragAndDrop')]
class DragAndDropActionFeatureTest extends BaseFeatureTestCase
{
    public function testDragsElementsToOtherElements(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act
        $espresso->navigateTo('/actions/drag-and-drop.html');

        $espresso->onElement(withText('Item A'))
            ->perform(dragAndDrop(withClass('dropzone')));

        $espresso->onElement(withClass('dropzone'))
            ->check(matches(withText('Item A dropped')));
    }

    public function testDragsAndDropsFromFarAway(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act
        $espresso->navigateTo('/actions/drag-and-drop.html');

        $espresso->onElement(withText('Item C'))
            ->perform(dragAndDrop(withClass('dropzone')));

        $espresso->onElement(withClass('dropzone'))
            ->check(matches(withText('Item C dropped')));
    }
}
