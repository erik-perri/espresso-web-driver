<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverElement;

final readonly class ClickAction implements ActionInterface
{
    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        $element->click();

        return true;
    }

    public function __toString(): string
    {
        return 'click';
    }
}
