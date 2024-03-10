<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Action\ActionInterface;

class PerformException extends EspressoWebDriverException
{
    public function __construct(ActionInterface $action, ?string $element = null, ?string $reason = null)
    {
        parent::__construct($this->buildMessage($action, $element, $reason));
    }

    private function buildMessage(ActionInterface $action, ?string $element, ?string $reason): string
    {
        if ($element === null) {
            return $reason !== null
                ? sprintf('Failed to perform action %1$s, %2$s', $action, $reason)
                : sprintf('Failed to perform action %1$s', $action);
        }

        return $reason !== null
            ? sprintf('Failed to perform action %1$s on %2$s, %3$s', $action, $element, $reason)
            : sprintf('Failed to perform action %1$s on %2$s', $action, $element);
    }
}
