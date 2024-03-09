<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\matches;
use function EspressoWebDriver\submit;
use function EspressoWebDriver\withId;
use function EspressoWebDriver\withText;

#[CoversClass(SubmitAction::class)]
#[CoversFunction('EspressoWebDriver\submit')]
class SubmitActionFeatureTest extends BaseFeatureTestCase
{
    #[DataProvider('elementIdProvider')]
    public function testSubmitsFormFromInputs(string $id): void
    {
        // Arrange
        $espresso = $this->espresso();

        // Act
        $espresso->navigateTo('/actions/submit.html')
            ->onElement(withId($id))
            ->perform(submit());

        // Assert
        $espresso->onElement(withId('status'))
            ->check(matches(withText('Submitted')));
    }

    /**
     * @return array<string, array{id: string}>
     */
    public static function elementIdProvider(): array
    {
        return [
            'selects' => [
                'id' => 'test-a',
            ],
            'inputs' => [
                'id' => 'test-b',
            ],
        ];
    }
}
