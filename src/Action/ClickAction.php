<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use Facebook\WebDriver\WebDriverElement;

final readonly class ClickAction implements ActionInterface
{
    public function perform(WebDriverElement $element): bool
    {
        $element->click();

        return true;
    }
}
