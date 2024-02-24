<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriverElement;

final readonly class ScrollToAction implements ActionInterface
{
    /**
     * @throws PerformException
     */
    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        if (!($context->driver instanceof JavaScriptExecutor)) {
            throw new PerformException(
                action: $this,
                element: $context->options->elementLogger->describe($element),
                reason: 'driver does not have access to executeScript',
            );
        }

        $context->driver->executeScript('arguments[0].scrollIntoView(true);', [$element]);

        return true;
    }

    public function __toString(): string
    {
        return 'scrollTo';
    }
}
