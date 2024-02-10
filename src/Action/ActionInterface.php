<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use Facebook\WebDriver\WebDriverElement;

interface ActionInterface
{
    public function perform(WebDriverElement $element): bool;
}
