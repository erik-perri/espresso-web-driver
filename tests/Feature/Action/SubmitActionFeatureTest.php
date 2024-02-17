<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Action;

use EspressoWebDriver\Action\SubmitAction;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Tests\Feature\BaseFeatureTestCase;
use EspressoWebDriver\Tests\Helpers\PhpunitReporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function EspressoWebDriver\matches;
use function EspressoWebDriver\submit;
use function EspressoWebDriver\usingDriver;
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
        $driver = $this->driver()->get($this->mockStaticUrl('actions/submit.html'));

        $options = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            assertionReporter: new PhpunitReporter,
        );

        $espresso = usingDriver($driver, $options);

        // Act
        $espresso
            ->onElement(withId($id))
            ->perform(submit());

        // Assert
        $espresso
            ->onElement(withId('status'))
            ->check(matches(withText('Submitted')));
    }

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
