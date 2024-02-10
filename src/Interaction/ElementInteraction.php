<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use Facebook\WebDriver\WebDriverElement;
use RuntimeException;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(private WebDriverElement $element)
    {
        //
    }

    public function check(AssertionInterface $assertion): InteractionInterface
    {
        if (!$assertion->assert($this->element)) {
            throw new RuntimeException('Assertion failed');
        }

        return $this;
    }

    public function perform(ActionInterface ...$action): InteractionInterface
    {
        foreach ($action as $act) {
            if (!$act->perform($this->element)) {
                throw new RuntimeException('Action failed');
            }
        }

        return $this;
    }
}
