<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;

class NoRootElementException extends EspressoWebDriverException
{
    public function __construct(MatcherInterface $matcher)
    {
        parent::__construct(sprintf('no root element found using %1$s', $matcher));
    }
}
