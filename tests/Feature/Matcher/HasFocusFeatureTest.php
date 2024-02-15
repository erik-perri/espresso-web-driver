<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Feature\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exception\NoParentException;
use EspressoWebDriver\Matcher\HasFocusMatcher;
use EspressoWebDriver\Reporter\PhpunitReporter;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\click;
use function EspressoWebDriver\hasFocus;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\sendKeys;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;

#[CoversClass(HasFocusMatcher::class)]
#[CoversClass(NoParentException::class)]
#[CoversFunction('EspressoWebDriver\hasFocus')]
class HasFocusFeatureTest extends BaseFeatureTestCase
{
    public function testFocusWorksOnElementsWithTabIndex(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);
        $div = $espresso->onElement(withId('test-a'));

        // Act and Assert
        $div->check(matches(not(hasFocus())));

        $espresso
            ->onElement(withTagName('body'))
            ->perform(sendKeys(WebDriverKeys::TAB));

        $div->check(matches(hasFocus()));
    }

    public function testFocusWorksOnLinks(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);
        $link = $espresso->onElement(withId('test-b'));

        // Act and Assert
        $link->check(matches(not(hasFocus())));

        $espresso
            ->onElement(withTagName('body'))
            ->perform(sendKeys(WebDriverKeys::TAB, WebDriverKeys::TAB));

        $link->check(matches(hasFocus()));
    }

    public function testFocusWorksOnSelects(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withId('test-c'))
            ->check(matches(not(hasFocus())))
            ->perform(click())
            ->check(matches(hasFocus()));
    }

    public function testFocusWorksOnInputs(): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        $input = $espresso->onElement(withId('test-d'));
        $label = $espresso->onElement(withClass('label-d'));

        // Act and Assert
        $input->check(matches(not(hasFocus())));

        $label->perform(click());

        $input->check(matches(hasFocus()));
    }

    public function testThrowsExceptionWhenCallingOnTheTopLevelElement(): void
    {
        // Expectations
        $this->expectException(NoParentException::class);
        $this->expectExceptionMessage('Unable to locate a parent while checking <html> for focused');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withTagName('html'))
            ->check(matches(hasFocus()));

        // Assert
        // No assertions, only expectations.
    }

    public function testThrowsExceptionWhenCallingNegativeOnTheTopLevelElement(): void
    {
        // Expectations
        $this->expectException(NoParentException::class);
        $this->expectExceptionMessage('Unable to locate a parent while checking <html> for focused');

        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('matchers/has-focus.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso->onElement(withTagName('html'))
            ->check(matches(not(hasFocus())));

        // Assert
        // No assertions, only expectations.
    }
}
