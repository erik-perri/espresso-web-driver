<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Core;

use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Tests\Helpers\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

use function EspressoWebDriver\click;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withText;

#[CoversClass(AmbiguousElementException::class)]
#[CoversClass(EspressoCore::class)]
class EspressoCoreTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

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

        $mockOptions = new EspressoOptions();

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
        $mockHtmlElement = $this->createMockWebDriverElement('html', children: []);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions();

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
        $this->expectExceptionMessage('4 elements found for withText(mock)');

        // Arrange
        $mockElementOne = $this->createMockWebDriverElement('div');
        $mockElementTwo = $this->createMockWebDriverElement('div');
        $mockElementThree = $this->createMockWebDriverElement('div');
        $mockElementFour = $this->createMockWebDriverElement('div');

        $mockHtmlElement = $this->createMockWebDriverElement('html', children: [
            $mockElementOne,
            $mockElementTwo,
            $mockElementThree,
            $mockElementFour,
        ]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions();

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
        $this->expectExceptionMessage('2 elements found for withText(mock)');

        // Arrange
        $mockElementOne = $this->createMockWebDriverElement('div');
        $mockElementTwo = $this->createMockWebDriverElement('div');

        $mockHtmlElement = $this->createMockWebDriverElement('html', children: [
            $mockElementOne,
            $mockElementTwo,
        ]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions();

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
        $mockContainerElement = $this->createMockWebDriverElement('div', children: [
            $this->createMockWebDriverElement('div'),
        ]);
        $mockContainerElement
            // If we were not re-contained, this would fail since the html element would get the findElements call.
            ->expects($this->once())
            ->method('findElements')
            ->willReturn([]);

        $mockHtmlElement = $this->createMockWebDriverElement('html', children: [
            $mockContainerElement,
        ]);

        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($mockHtmlElement);

        $mockOptions = new EspressoOptions();

        $espresso = new EspressoCore($mockDriver, $mockOptions);
        $containedEspresso = $espresso->inContainer(withText('mock'));

        // Act
        $result = $containedEspresso->onElement(withText('mock'));

        // Assert
        $this->assertInstanceOf(InteractionInterface::class, $result);
    }
}
