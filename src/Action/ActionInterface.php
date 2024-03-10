<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

interface ActionInterface
{
    public function perform(WebDriverElement $target, ?MatcherInterface $container, EspressoContext $context): bool;

    public function __toString(): string;
}
