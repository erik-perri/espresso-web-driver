<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Processor\MatchResult;

final readonly class ClickAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $interactableParent = $context->options->elementLocator
            ->findNonScreenReaderParent($target->single(), $target->container?->single());

        $interactableParent->click();

        return true;
    }

    public function __toString(): string
    {
        return 'click';
    }
}
