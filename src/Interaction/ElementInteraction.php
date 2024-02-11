<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\WebDriverElement;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(
        private WebDriverElement $element,
        private EspressoContext $context,
    ) {
        //
    }

    /**
     * @throws AssertionFailedException
     */
    public function check(AssertionInterface $assertion): InteractionInterface
    {
        $result = $assertion->assert($this->element, $this->context);

        $this->context->options->assertionReporter?->report(
            $result,
            sprintf('Failed asserting that %s is true', $assertion),
        );

        if (!$result) {
            throw new AssertionFailedException($assertion);
        }

        return $this;
    }

    /**
     * @throws PerformException
     */
    public function perform(ActionInterface ...$actions): InteractionInterface
    {
        foreach ($actions as $action) {
            if (!$action->perform($this->element, $this->context)) {
                throw new PerformException($action);
            }
        }

        return $this;
    }
}
