<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverElement;

interface ActionInterface
{
    public function perform(WebDriverElement $target, EspressoContext $context): bool;

    public function __toString(): string;
}
