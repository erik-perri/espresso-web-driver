<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class ClickAction implements ActionInterface
{
    public function perform(WebDriverElement $target, ?MatcherInterface $container, EspressoContext $context): bool
    {
        $target->click();

        return true;
    }

    public function __toString(): string
    {
        return 'click';
    }
}
