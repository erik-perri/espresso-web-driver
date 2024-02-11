<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Action\ActionInterface;

class PerformException extends EspressoWebDriverException
{
    public function __construct(ActionInterface $action)
    {
        parent::__construct(sprintf('Failed to perform action %1$s', $action));
    }
}
