<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;

final readonly class ClickAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $target->single()->click();

        return true;
    }

    public function __toString(): string
    {
        return 'click';
    }
}
