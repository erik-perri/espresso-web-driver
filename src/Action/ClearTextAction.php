<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverElement;

final readonly class ClearTextAction implements ActionInterface
{
    public function perform(WebDriverElement $target, EspressoContext $context): bool
    {
        $target->clear();

        return true;
    }

    public function __toString(): string
    {
        return 'clearText';
    }
}
