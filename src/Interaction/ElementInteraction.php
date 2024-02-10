<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\WebDriverElement;
use RuntimeException;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(private WebDriverElement $element, private EspressoOptions $options)
    {
        //
    }

    public function check(AssertionInterface $assertion): InteractionInterface
    {
        if (!$assertion->assert($this->element, $this->options)) {
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
