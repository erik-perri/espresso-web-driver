<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Processor\MatchResult;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\Exception\ElementNotInteractableException;

final readonly class ClickAction implements ActionInterface
{
    /**
     * @noinspection PhpRedundantCatchClauseInspection
     */
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $interactableParent = $context->options->elementLocator
            ->findNonScreenReaderParent($target->single(), $target->container?->single());

        // TODO Build retry logic in a configurable way
        $animationWaitIntervalInMicroseconds = 250 * 1000;

        try {
            $interactableParent->click();

            return true;
        } catch (ElementClickInterceptedException|ElementNotInteractableException) {
            usleep($animationWaitIntervalInMicroseconds);

            $interactableParent->click();
        }

        return true;
    }

    public function __toString(): string
    {
        return 'click';
    }
}
