<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Core;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\EspressoCore;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Traits\MocksWebDriverElement;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EspressoCore::class)]
class EspressoCoreTest extends BaseUnitTestCase
{
    use MocksWebDriverElement;

    public function testNavigateToPassesThroughToDriver(): void
    {
        // Arrange
        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('get')
            ->with('https://example.com');

        $mockOptions = new EspressoOptions;

        $core = new EspressoCore($mockDriver, $mockOptions);

        // Act
        $core->navigateTo('https://example.com');

        // Assert
        // No assertions, only expectations.
    }

    public function testInContainerPassesThroughMatchToNextCall(): void
    {
        // Arrange
        $mockDriver = $this->createMock(WebDriver::class);
        $mockDriver
            ->expects($this->once())
            ->method('findElement')
            ->willReturn($this->createMockWebDriverElement('html'));

        $core = new EspressoCore($mockDriver, new EspressoOptions);

        $mockContainerMatcher = $this->createMock(MatcherInterface::class);
        $mockContainerMatcher->expects($this->once())
            ->method('match')
            ->willReturn([$this->createMockWebDriverElement('div')]);

        $mockContainerElement = $this->createMockWebDriverElement('div');
        $mockContainerResult = new MatchResult($mockContainerMatcher, [$mockContainerElement]);

        $mockElement = $this->createMockWebDriverElement('div');
        $mockElementMatcher = $this->createMock(MatcherInterface::class);
        $mockElementMatcher->expects($this->once())
            ->method('match')
            ->with($mockContainerResult, $this->isInstanceOf(EspressoContext::class))
            ->willReturn([$mockElement]);

        $mockAction = $this->createMock(ActionInterface::class);
        $mockAction->expects($this->once())
            ->method('perform')
            ->with($mockElement, $mockContainerMatcher, $this->isInstanceOf(EspressoContext::class))
            ->willReturn(true);

        // Act
        $core->inContainer($mockContainerMatcher)
            ->onElement($mockElementMatcher)
            ->perform($mockAction);

        // Assert
        // No assertions, only expectations.
    }
}
