<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use Facebook\WebDriver\WebDriverElement;

interface ElementLocatorInterface
{
    public function findNonScreenReaderParent(
        WebDriverElement $element,
        ?WebDriverElement $container,
    ): WebDriverElement;
}
