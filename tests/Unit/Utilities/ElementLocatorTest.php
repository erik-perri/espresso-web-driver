<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\ElementLocator;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ElementLocator::class)]
class ElementLocatorTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testReturnsParentIfElementIsScreenReaderElement(): void
    {
        // Arrange
        $elementLocator = new ElementLocator(['sr-only']);

        $mockElement = $this->createMockWebDriverElement('div', ['class' => 'sr-only']);

        $mockParent = $this->createMockWebDriverElement('div');

        $mockElement
            ->expects($this->once())
            ->method('findElement')
            ->with(WebDriverBy::xpath('..'))
            ->willReturn($mockParent);

        // Act
        $result = $elementLocator->findNonScreenReaderParent($mockElement, null);

        // Assert
        $this->assertSame($mockParent, $result);
    }

    public function testReturnsElementWhenParentCannotBeFound(): void
    {
        // Arrange
        $elementLocator = new ElementLocator(['sr-only']);

        $mockElement = $this->createMockWebDriverElement('div', ['class' => 'sr-only']);

        $mockElement
            ->expects($this->once())
            ->method('findElement')
            ->with(WebDriverBy::xpath('..'))
            ->willThrowException(new NoSuchElementException(''));

        // Act
        $result = $elementLocator->findNonScreenReaderParent($mockElement, null);

        // Assert
        $this->assertSame($mockElement, $result);
    }

    public function testReturnsInputElementWhenElementHasNoClasses(): void
    {
        // Arrange
        $elementLocator = new ElementLocator(['sr-only']);

        $mockElement = $this->createMockWebDriverElement('input');

        // Act
        $result = $elementLocator->findNonScreenReaderParent($mockElement, null);

        // Assert
        $this->assertSame($mockElement, $result);
    }

    public function testReturnsInputElementIfItContainsOverlappingButNotExistScreenReaderClass(): void
    {
        // Arrange
        $elementLocator = new ElementLocator(['sr-only']);

        $mockElement = $this->createMockWebDriverElement('input', ['class' => 'ssr-only']);

        $mockElement->expects($this->never())
            ->method('findElement');

        // Act
        $result = $elementLocator->findNonScreenReaderParent($mockElement, null);

        // Assert
        $this->assertSame($mockElement, $result);
    }
}
