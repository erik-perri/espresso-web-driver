<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\FocusAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\focus;
use function EspressoWebDriver\isFocused;
use function EspressoWebDriver\matches;
use function EspressoWebDriver\withId;

#[CoversClass(FocusAction::class)]
#[CoversFunction('EspressoWebDriver\focus')]
class FocusActionFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('elementIdProvider')]
    public function testFocusesElements(string $id): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act and Assert
        $espresso
            ->navigateTo('/actions/focus.html')
            ->onElement(withId($id))
            ->perform(focus())
            ->check(matches(isFocused()));
    }

    /**
     * @return array<string, array{id: string}>
     */
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
