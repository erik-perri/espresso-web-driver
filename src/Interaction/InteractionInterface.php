<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;

interface InteractionInterface
{
    public function check(AssertionInterface $assertion): self;

    public function perform(ActionInterface ...$action): self;
}
