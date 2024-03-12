<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;

final readonly class ClearTextAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $target->single()->clear();

        return true;
    }

    public function __toString(): string
    {
        return 'clearText';
    }
}
