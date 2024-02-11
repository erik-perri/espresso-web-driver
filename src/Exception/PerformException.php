<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Action\ActionInterface;

class PerformException extends EspressoWebDriverException
{
    public function __construct(ActionInterface $action, ?string $reason = null)
    {
        parent::__construct(
            $reason !== null
                ? sprintf('Failed to perform action %1$s, %2$s', $action, $reason)
                : sprintf('Failed to perform action %1$s', $action));
    }
}
