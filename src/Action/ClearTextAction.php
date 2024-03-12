<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\JavaScriptExecutor;

final readonly class ClearTextAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $targetElement = $target->single();

        $targetElement->clear();

        if ($context->driver instanceof JavaScriptExecutor) {
            $context->driver->executeScript(
                'arguments[0].dispatchEvent(new Event("input", { bubbles: true }));',
                [$targetElement],
            );
        }

        return true;
    }

    public function __toString(): string
    {
        return 'clearText';
    }
}
