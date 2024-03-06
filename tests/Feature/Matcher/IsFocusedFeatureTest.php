<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\NoParentException;
use EspressoWebDriver\Matcher\IsFocusedMatcher;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\isFocused;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\sendKeys;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;

#[CoversClass(IsFocusedMatcher::class)]
#[CoversClass(NoParentException::class)]
#[CoversFunction('EspressoWebDriver\isFocused')]
class IsFocusedFeatureTest extends BaseFeatureTestCase
{
    public function testFocusWorksOnElementsWithTabIndex(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);
        $div = $espresso->onElement(withId('test-a'));

        // Act and Assert
        $div->check(matches(not(isFocused())));

        $espresso
            ->onElement(withTagName('body'))
            ->perform(sendKeys(WebDriverKeys::TAB));

        $div->check(matches(isFocused()));
    }

    public function testFocusWorksOnLinks(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);
        $link = $espresso->onElement(withId('test-b'));

        // Act and Assert
        $link->check(matches(not(isFocused())));

        $espresso
            ->onElement(withTagName('body'))
            ->perform(sendKeys(WebDriverKeys::TAB, WebDriverKeys::TAB));

        $link->check(matches(isFocused()));
    }

    public function testFocusWorksOnSelects(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withId('test-c'))
            ->check(matches(not(isFocused())))
            ->perform(click())
            ->check(matches(isFocused()));
    }

    public function testFocusWorksOnInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        $input = $espresso->onElement(withId('test-d'));
        $label = $espresso->onElement(withClass('label-d'));

        // Act and Assert
        $input->check(matches(not(isFocused())));

        $label->perform(click());

        $input->check(matches(isFocused()));
    }

    public function testThrowsExceptionWhenCallingOnTheTopLevelElement(): void
    {
        // Expectations
        $this->expectException(NoParentException::class);
        $this->expectExceptionMessage('Unable to locate a parent while checking html for isFocused');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withTagName('html'))
            ->check(matches(isFocused()));

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionWhenCallingNegativeOnTheTopLevelElement(): void
    {
        // Expectations
        $this->expectException(NoParentException::class);
        $this->expectExceptionMessage('Unable to locate a parent while checking html for isFocused');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions();

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withTagName('html'))
            ->check(matches(not(isFocused())));

        // Assert
        // No assertions, only expectations.
    }
}