<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;

final readonly class ClickAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $interactableParent = $context->options->elementLocator
            ->findNonScreenReaderParent($target->single(), $target->container?->single());

        // TODO Build retry logic in a configurable way
        $waitIntervalInMicroseconds = 250 * 1000;

        try {
            $interactableParent->click();

            return true;
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (ElementClickInterceptedException $e) {
            usleep($waitIntervalInMicroseconds);

            $interactableParent->click();
        }

        return true;
    }

    public function __toString(): string
    {
        return 'click';
    }
}
