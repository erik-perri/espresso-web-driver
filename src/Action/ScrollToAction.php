<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\JavaScriptExecutor;

final readonly class ScrollToAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $target = $target->single();

        if (!($context->driver instanceof JavaScriptExecutor)) {
            throw new PerformException(
                action: $this,
                element: $context->options->elementLogger->describe($target),
                reason: 'driver does not have access to executeScript',
            );
        }

        $context->driver->executeScript('arguments[0].scrollIntoView(true);', [$target]);

        return true;
    }

    public function __toString(): string
    {
        return 'scrollTo';
    }
}
