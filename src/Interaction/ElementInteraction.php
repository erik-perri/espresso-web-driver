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
            throw new RuntimeException(sprintf('Assertion failed [%1$s]', $assertion));
        }

        return $this;
    }

    public function perform(ActionInterface ...$actions): InteractionInterface
    {
        foreach ($actions as $action) {
            if (!$action->perform($this->element)) {
                throw new RuntimeException(sprintf('Action failed [%1$s]', $action));
            }
        }

        return $this;
    }
}
