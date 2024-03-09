<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SendKeysAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use Facebook\WebDriver\WebDriverKeys;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function EspressoWebDriver\focus;
use function EspressoWebDriver\isDisplayed;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\not;
use function EspressoWebDriver\sendKeys;
use function EspressoWebDriver\withClass;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withTagName;
use function EspressoWebDriver\withValue;

#[CoversClass(SendKeysAction::class)]
#[CoversFunction('EspressoWebDriver\sendKeys')]
class SendKeysActionFeatureTest extends BaseFeatureTestCase
{
    public function testSendsKeysToInputs(): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso
            ->navigateTo('/actions/send-keys.html')
            ->onElement(withId('test-a'))
            ->perform(focus(), sendKeys(WebDriverKeys::END, WebDriverKeys::BACKSPACE))
            ->check(matches(withValue('Value ')));
    }

    public function testSendsKeysToBrowser(): void
    {
        // Arrange
        $espresso = $this->espresso();

        $modal = $espresso->onElement(withClass('modal'));
        $button = $espresso->onElement(withTagName('button'));

        // Act and Assert
        $espresso->navigateTo('/actions/send-keys.html');

        $modal->check(matches(not(isDisplayed())));

        $button->perform(focus(), sendKeys(WebDriverKeys::SPACE));

        $modal->check(matches(isDisplayed()));

        $button->perform(sendKeys(WebDriverKeys::ESCAPE));

        $modal->check(matches(not(isDisplayed())));
    }
}
