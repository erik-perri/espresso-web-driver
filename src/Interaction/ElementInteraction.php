<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Exceptions\AssertionFailedException;
use EspressoWebDriver\Exceptions\PerformException;
use Facebook\WebDriver\WebDriverElement;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(private WebDriverElement $element, private EspressoOptions $options)
    {
        //
    }

    /**
     * @throws AssertionFailedException
     */
    public function check(AssertionInterface $assertion): InteractionInterface
    {
        if (!$assertion->assert($this->element, $this->options)) {
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
            if (!$action->perform($this->element)) {
                throw new PerformException($action);
            }
        }

        return $this;
    }
}
