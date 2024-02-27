<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\FocusAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Utilities\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\focus;
use function EspressoWebDriver\hasFocus;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\usingDriver;
use function EspressoWebDriver\withId;

#[CoversClass(FocusAction::class)]
#[CoversFunction('EspressoWebDriver\focus')]
class FocusActionFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('elementIdProvider')]
    public function testFocusesElements(string $id): void
    {
        // Arrange
        $driver = $this->driver()->get($this->mockStaticUrl('actions/focus.html'));

        $options = new EspressoOptions(assertionReporter: new PhpunitReporter);

        $espresso = usingDriver($driver, $options);

        // Act and Assert
        $espresso
            ->onElement(withId($id))
            ->perform(focus())
            ->check(matches(hasFocus()));
    }

    public static function elementIdProvider(): array
    {
        return [
            'tab indexes' => [
                'id' => 'test-a',
            ],
            'anchors' => [
                'id' => 'test-b',
            ],
            'selects' => [
                'id' => 'test-c',
            ],
            'inputs' => [
                'id' => 'test-d',
            ],
        ];
    }
}
