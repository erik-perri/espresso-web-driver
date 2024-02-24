<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\ElementPathLogger;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ElementPathLogger::class)]
class ElementPathLoggerTest extends BaseUnitTestCase
{
    public function testLogsIndexRelativeToSiblingsOfSameType(): void
    {
        // Arrange
        $htmlElement = $this->createMock(WebDriverElement::class);
        $htmlElement->expects($this->once())
            ->method('getTagName')
            ->willReturn('html');

        $bodyElement = $this->createMock(WebDriverElement::class);
        $bodyElement->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('body');

        $bodyElement->expects($this->once())
            ->method('findElement')
            ->willReturn($htmlElement);

        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->exactly(2))
            ->method('getTagName')
            ->willReturn('div');

        $element->expects($this->exactly(3))
            ->method('getID')
            ->willReturn('test');

        $element->expects($this->once())
            ->method('findElement')
            ->willReturn($bodyElement);

        $htmlElement->expects($this->once())
            ->method('findElements')
            ->willReturn([$bodyElement]);

        $bodyElement->expects($this->once())
            ->method('findElements')
            ->willReturn([
                $this->createMock(WebDriverElement::class),
                $element,
            ]);

        $pathLogger = new ElementPathLogger();

        // Act
        $elementPath = $pathLogger->describe($element);

        // Assert
        $this->assertSame('html/body/div[2]', $elementPath);
    }

    public function testReturnsBaseTagForHtmlElement(): void
    {
        // Arrange
        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->once())
            ->method('getTagName')
            ->willReturn('html');

        $element->expects($this->once())
            ->method('findElement')
            ->willThrowException(new NoSuchElementException(''));

        $pathLogger = new ElementPathLogger();

        // Act
        $elementPath = $pathLogger->describe($element);

        // Assert
        $this->assertSame('html', $elementPath);
    }

    public function testCanDisambiguateHtmlAndBodyElements(): void
    {
        // Arrange
        $htmlElement = $this->createMock(WebDriverElement::class);
        $htmlElement->expects($this->once())
            ->method('getTagName')
            ->willReturn('html');

        $bodyElement = $this->createMock(WebDriverElement::class);
        $bodyElement->expects($this->exactly(2))
            ->method('getTagName')
            ->willReturn('body');

        $bodyElement->expects($this->once())
            ->method('findElement')
            ->willReturn($htmlElement);

        $bodyElement->expects($this->exactly(3))
            ->method('getID')
            ->willReturn('test-body');

        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('div');

        $element->expects($this->exactly(2))
            ->method('getID')
            ->willReturn('test');

        $element->expects($this->once())
            ->method('findElement')
            ->willReturn($bodyElement);

        $htmlElement->expects($this->once())
            ->method('findElements')
            ->willReturn([
                $this->createMock(WebDriverElement::class),
                $bodyElement,
            ]);

        $bodyElement->expects($this->once())
            ->method('findElements')
            ->willReturn([$element]);

        $pathLogger = new ElementPathLogger();

        // Act
        $elementPath = $pathLogger->describe($element);

        // Assert
        $this->assertSame('html/body[2]/div[1]', $elementPath);
    }

    public function testIncludesIdAttributeInPathIfFound(): void
    {
        // Arrange
        $bodyElement = $this->createMock(WebDriverElement::class);
        $bodyElement->expects($this->once())
            ->method('getTagName')
            ->willReturn('body');

        $bodyElement->expects($this->once())
            ->method('findElement')
            ->willThrowException(new NoSuchElementException(''));

        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('div');

        $element->expects($this->exactly(2))
            ->method('getAttribute')
            ->with('id')
            ->willReturn('mock-id');

        $element->expects($this->once())
            ->method('findElement')
            ->willReturn($bodyElement);

        $bodyElement->expects($this->once())
            ->method('findElements')
            ->willReturn([$element]);

        $pathLogger = new ElementPathLogger();

        // Act
        $elementPath = $pathLogger->describe($element);

        // Assert
        $this->assertSame('body/div[@id="mock-id"][1]', $elementPath);
    }

    public function testDoesNotPretendToKnowTheIndexIfElementIsNotInSiblings(): void
    {
        // Arrange
        $bodyElement = $this->createMock(WebDriverElement::class);
        $bodyElement->expects($this->once())
            ->method('getTagName')
            ->willReturn('body');

        $bodyElement->expects($this->once())
            ->method('findElement')
            ->willThrowException(new NoSuchElementException(''));

        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->exactly(3))
            ->method('getTagName')
            ->willReturn('div');

        $element->expects($this->once())
            ->method('getID')
            ->willReturn('test');

        $element->expects($this->once())
            ->method('findElement')
            ->willReturn($bodyElement);

        $bodyElement->expects($this->once())
            ->method('findElements')
            ->willReturn([$this->createMock(WebDriverElement::class)]);

        $pathLogger = new ElementPathLogger();

        // Act
        $elementPath = $pathLogger->describe($element);

        // Assert
        $this->assertSame('body/div[?]', $elementPath);
    }
}
