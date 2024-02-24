<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Utilities\ElementAttributeLogger;
use Facebook\WebDriver\WebDriverElement;

class PerformException extends EspressoWebDriverException
{
    public function __construct(ActionInterface $action, WebDriverElement $element, ?string $reason = null)
    {
        $logger = new ElementAttributeLogger();
        $elementLog = $logger->describe($element);

        parent::__construct(
            $reason !== null
                ? sprintf('Failed to perform action %1$s on %2$s, %3$s', $action, $elementLog, $reason)
                : sprintf('Failed to perform action %1$s on %2$s', $action, $elementLog));
    }
}
