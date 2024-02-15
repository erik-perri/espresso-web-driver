<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Utilities;

use EspressoWebDriver\Exception\EspressoWebDriverException;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use EspressoWebDriver\Utilities\ElementDisplayChecker;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverPoint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;

#[CoversClass(ElementDisplayChecker::class)]
#[CoversClass(EspressoWebDriverException::class)]
class ElementDisplayCheckerTest extends BaseUnitTestCase
{
    public function testThrowsExceptionIfDriverDoesNotHaveAccessToExecuteScript(): void
    {
        // Expectations
        $this->expectException(EspressoWebDriverException::class);
        $this->expectExceptionMessage('Cannot check displayed state, driver does not have access to executeScript');

        // Arrange
        $mockDriver = $this->createMock(WebDriver::class);

        $mockElement = $this->createMock(WebDriverElement::class);
        $mockElement
            ->method('getTagName')
            ->willReturn('mock');

        $checker = new ElementDisplayChecker($mockDriver);

        // Act
        $checker->isDisplayed($mockElement);
    }

    public function testReturnsFalseWhenElementClaimsHidden(): void
    {
        // Arrange
        /**
         * @var MockObject|WebDriver&JavaScriptExecutor $mockDriver
         */
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver
            ->method('executeScript')
            ->willReturnMap([
                ['return window.scrollX;', 0],
                ['return window.scrollY;', 0],
                ['return window.innerWidth || document.documentElement.clientWidth;', 100],
                ['return window.innerHeight || document.documentElement.clientHeight;', 100],
            ]);

        $mockElement = $this->createMock(WebDriverElement::class);
        $mockElement
            ->method('isDisplayed')
            ->willReturn(false);
        $mockElement
            ->method('getTagName')
            ->willReturn('mock');
        $mockElement
            ->method('getLocation')
            ->willReturn(new WebDriverPoint(0, 0));
        $mockElement
            ->method('getSize')
            ->willReturn(new WebDriverDimension(10, 10));

        $checker = new ElementDisplayChecker($mockDriver);

        // Act
        $result = $checker->isDisplayed($mockElement);

        // Assert
        $this->assertFalse($result, 'Failed asserting that element was not displayed.');
    }

    #[DataProvider('boundsProvider')]
    public function testChecksWithinBounds(
        WebDriverPoint $scrollPosition,
        WebDriverDimension $viewportSize,
        WebDriverPoint $elementLocation,
        WebDriverDimension $elementSize,
        bool $expected,
    ): void {
        // Arrange
        /**
         * @var MockObject|WebDriver&JavaScriptExecutor $mockDriver
         */
        $mockDriver = $this->createMockForIntersectionOfInterfaces([WebDriver::class, JavaScriptExecutor::class]);
        $mockDriver
            ->method('executeScript')
            ->willReturnMap([
                ['return window.scrollX;', $scrollPosition->getX()],
                ['return window.scrollY;', $scrollPosition->getY()],
                ['return window.innerWidth || document.documentElement.clientWidth;', $viewportSize->getWidth()],
                ['return window.innerHeight || document.documentElement.clientHeight;', $viewportSize->getHeight()],
            ]);

        $mockElement = $this->createMock(WebDriverElement::class);
        $mockElement
            ->method('isDisplayed')
            ->willReturn(true);
        $mockElement
            ->method('getLocation')
            ->willReturn($elementLocation);
        $mockElement
            ->method('getSize')
            ->willReturn($elementSize);

        $checker = new ElementDisplayChecker($mockDriver);

        // Act
        $result = $checker->isDisplayed($mockElement);

        // Assert
        $this->assertEquals(
            $expected,
            $result,
            $expected
                ? 'Failed asserting that element was displayed.'
                : 'Failed asserting that element was not displayed.',
        );
    }

    public static function boundsProvider(): array
    {
        return [
            'element is out of bounds' => [
                'scrollPosition' => new WebDriverPoint(0, 0),
                'viewportSize' => new WebDriverDimension(100, 100),
                'elementLocation' => new WebDriverPoint(101, 101),
                'elementSize' => new WebDriverDimension(10, 10),
                'expected' => false,
            ],
            'element is within bounds' => [
                'scrollPosition' => new WebDriverPoint(0, 0),
                'viewportSize' => new WebDriverDimension(100, 100),
                'elementLocation' => new WebDriverPoint(0, 0),
                'elementSize' => new WebDriverDimension(10, 10),
                'expected' => true,
            ],
            'element is partially within bounds' => [
                'scrollPosition' => new WebDriverPoint(0, 0),
                'viewportSize' => new WebDriverDimension(100, 100),
                'elementLocation' => new WebDriverPoint(90, 90),
                'elementSize' => new WebDriverDimension(20, 20),
                'expected' => false,
            ],
        ];
    }
}
