<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\WebDriverElement;

final readonly class FocusAction implements ActionInterface
{
    /**
     * @throws PerformException
     */
    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        if (!method_exists($context->driver, 'executeScript')) {
            throw new PerformException($this, $element, 'driver does not have access to executeScript');
        }

        $context->driver->executeScript('arguments[0].focus();', [$element]);

        return true;
    }

    public function __toString(): string
    {
        return 'focus';
    }
}
