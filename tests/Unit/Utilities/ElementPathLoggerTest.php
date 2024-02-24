<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Helpers\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\ElementPathLogger;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ElementPathLogger::class)]
class ElementPathLoggerTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testLogsIndexRelativeToSiblingsOfSameType(): void
    {
        // Arrange
        $element = $this->createMockWebDriverElement('div');

        $this->createMockWebDriverElement(
            tagName: 'html',
            children: [
                $this->createMockWebDriverElement(
                    tagName: 'body',
                    children: [
                        $this->createMockWebDriverElement('div'),
                        $element,
                    ],
                ),
            ],
        );

        $pathLogger = new ElementPathLogger();

        // Act
        $elementPath = $pathLogger->describe($element);

        // Assert
        $this->assertSame('html/body/div[2]', $elementPath);
    }

    public function testReturnsBaseTagForHtmlElement(): void
    {
        // Arrange
        $element = $this->createMockWebDriverElement('html');

        $logger = new ElementPathLogger();

        // Act
        $result = $logger->describe($element);

        // Assert
        $this->assertSame('html', $result);
    }

    public function testCanDisambiguateHtmlAndBodyElements(): void
    {
        // Arrange
        $element = $this->createMockWebDriverElement('div');

        $this->createMockWebDriverElement(
            tagName: 'html',
            children: [
                $this->createMockWebDriverElement(tagName: 'body'),
                $this->createMockWebDriverElement(tagName: 'body', children: [$element]),
            ],
        );

        $logger = new ElementPathLogger();

        // Act
        $result = $logger->describe($element);

        // Assert
        $this->assertSame('html/body[2]/div[1]', $result);
    }

    public function testIncludesIdAttributeInPathIfFound(): void
    {
        // Arrange
        $element = $this->createMockWebDriverElement('div', ['id' => 'mock-id']);

        $this->createMockWebDriverElement('body', children: [$element]);

        $logger = new ElementPathLogger();

        // Act
        $result = $logger->describe($element);

        // Assert
        $this->assertSame('body/div[@id="mock-id"][1]', $result);
    }

    public function testDoesNotPretendToKnowTheIndexIfElementIsNotInSiblings(): void
    {
        // Arrange
        $element = $this->createMockWebDriverElement('div');

        $bodyElement = $this->createMockWebDriverElement('body', children: []);

        $element->expects($this->once())
            ->method('findElement')
            ->with(WebDriverBy::xpath('./parent::*'))
            ->willReturn($bodyElement);

        $logger = new ElementPathLogger();

        // Act
        $result = $logger->describe($element);

        // Assert
        $this->assertSame('body/div[?]', $result);
    }
}
