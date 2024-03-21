<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Core\EspressoContext;

final readonly class ActionProcessor implements ActionProcessorInterface
{
    public function process(ActionInterface $action, MatchResult $target, EspressoContext $context): bool
    {
        return $action->perform($target, $context);
    }
}
