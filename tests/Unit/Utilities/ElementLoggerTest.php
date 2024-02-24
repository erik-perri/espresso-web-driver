<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\ElementLogger;
use EspressoWebDriver\Utilities\ElementLoggerInterface;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ElementLogger::class)]
class ElementLoggerTest extends BaseUnitTestCase
{
    public function testUsesBothProvidedLoggers(): void
    {
        // Arrange
        $mockLoggerOne = $this->createMock(ElementLoggerInterface::class);
        $mockLoggerOne
            ->expects($this->once())
            ->method('describe')
            ->willReturn('<logger one>');

        $mockLoggerTwo = $this->createMock(ElementLoggerInterface::class);
        $mockLoggerTwo
            ->expects($this->once())
            ->method('describe')
            ->willReturn('<logger two>');

        $logger = new ElementLogger($mockLoggerOne, $mockLoggerTwo);

        $mockElement = $this->createMock(WebDriverElement::class);

        // Act
        $result = $logger->describe($mockElement);

        // Assert
        $this->assertSame('<logger one> <logger two>', $result);
    }
}
