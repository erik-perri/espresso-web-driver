<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\PerformException;

interface ActionProcessorInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException|PerformException
     */
    public function process(ActionInterface $action, MatchResult $target, EspressoContext $context): bool;
}
