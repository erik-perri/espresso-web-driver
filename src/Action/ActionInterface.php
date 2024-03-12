<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\PerformException;

interface ActionInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException|PerformException
     */
    public function perform(MatchResult $target, EspressoContext $context): bool;

    public function __toString(): string;
}
