<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\WebDriver;

final readonly class MatchContext
{
    public function __construct(
        public WebDriver $driver,
        public bool $isNegated,
        public EspressoOptions $options,
    ) {
        //
    }
}
