<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use Facebook\WebDriver\WebDriver;

final readonly class EspressoContext
{
    public function __construct(
        public WebDriver $driver,
        public EspressoOptions $options,
        public bool $isNegated = false,
    ) {
        //
    }
}
