<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Core;

use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

use function EspressoWebDriver\click;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withText;

#[CoversClass(EspressoCore::class)]
class EspressoCoreTest extends BaseUnitTestCase
{
    public function testThrowsExceptionWhenUnableToLocateContainerElement(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for withTagName(html)');

        // Arrange
        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willThrowException(new NoSuchElementException(''));

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        // Act
        new EspressoCore($mockDriver, $mockOptions);

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionWhenNoElementsAreFound(): void
    {
        // Expectations
        $this->expectException(NoMatchingElementException::class);
        $this->expectExceptionMessage('No element found for withText(mock)');

        // Arrange
        $mockHtmlElement = $this->createMock(WebDriverElement::class);
        $mockHtmlElement
            ->expects($this->once())
            ->method('findElements')
            ->willReturn([]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        // Act
        $espresso = new EspressoCore($mockDriver, $mockOptions);
        $espresso->onElement(withText('mock'))
            ->perform(click());

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionWhenMultipleElementsAreFoundWhileMatching(): void
    {
        // Expectations
        $this->expectException(AmbiguousElementException::class);
        $this->expectExceptionMessage(
            "2 elements found for withText(mock)\n"
            ."html/mock[1] <mock>\n"
            .'html/mock[2] <mock>',
        );

        // Arrange
        $mockHtmlElement = $this->createMock(WebDriverElement::class);
        $mockHtmlElement->expects($this->exactly(2))
            ->method('getTagName')
            ->willReturn('html');

        $mockElementOne = $this->createMock(WebDriverElement::class);
        $mockElementOne->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockElementOne->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('mock');

        $mockElementOne->expects($this->exactly(3))
            ->method('getID')
            ->willReturn('mock-id');

        $mockElementTwo = $this->createMock(WebDriverElement::class);
        $mockElementTwo->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockElementTwo->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('mock');

        $mockHtmlElement
            ->expects($this->exactly(3))
            ->method('findElements')
            ->willReturn([
                $mockElementOne,
                $mockElementTwo,
            ]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        // Act
        $espresso = new EspressoCore($mockDriver, $mockOptions);
        $espresso->onElement(withText('mock'))
            ->perform(click());

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionWhenMultipleElementsAreFoundWhileContaining(): void
    {
        // Expectations
        $this->expectException(AmbiguousElementException::class);
        $this->expectExceptionMessage(
            "2 elements found for withText(mock)\n"
            ."html/mock[1] <mock>\n"
            .'html/mock[2] <mock>',
        );

        // Arrange
        $mockHtmlElement = $this->createMock(WebDriverElement::class);
        $mockHtmlElement->expects($this->exactly(2))
            ->method('getTagName')
            ->willReturn('html');

        $mockElementOne = $this->createMock(WebDriverElement::class);
        $mockElementOne->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockElementOne->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('mock');

        $mockElementOne->expects($this->exactly(3))
            ->method('getID')
            ->willReturn('mock-id');

        $mockElementTwo = $this->createMock(WebDriverElement::class);
        $mockElementTwo->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockElementTwo->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('mock');

        $mockHtmlElement
            ->expects($this->exactly(3))
            ->method('findElements')
            ->willReturn([
                $mockElementOne,
                $mockElementTwo,
            ]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        // Act
        $espresso = new EspressoCore($mockDriver, $mockOptions);
        $espresso->inContainer(withText('mock'))
            ->onElement(withId('mock'));

        // Assert
        // No assertions, only expectations.
    }

    public function testContainsResultsWhenExpected(): void
    {
        // Arrange
        $mockContainerElement = $this->createMock(WebDriverElement::class);
        $mockContainerElement
            ->expects($this->once())
            ->method('findElements')
            ->willReturn([
                $this->createMock(WebDriverElement::class),
            ]);

        $mockHtmlElement = $this->createMock(WebDriverElement::class);
        $mockHtmlElement
            // If we were not re-contained, this would fail
            ->expects($this->once())
            ->method('findElements')
            ->willReturn([
                $mockContainerElement,
            ]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions(waitTimeoutInSeconds: 0);

        $espresso = new EspressoCore($mockDriver, $mockOptions);
        $containedEspresso = $espresso->inContainer(withText('mock'));

        // Act
        $result = $containedEspresso->onElement(withText('mock'));

        // Assert
        $this->assertInstanceOf(InteractionInterface::class, $result);
    }
}
