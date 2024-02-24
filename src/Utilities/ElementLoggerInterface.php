<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use Facebook\WebDriver\WebDriverElement;

interface ElementLoggerInterface
{
    public function describe(WebDriverElement $element): string;
}
