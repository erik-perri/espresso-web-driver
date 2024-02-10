<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exceptions;

use EspressoWebDriver\Matcher\MatcherInterface;

class NoMatchingElementException extends EspressoWebDriverException
{
    public function __construct(MatcherInterface $matcher)
    {
        parent::__construct(sprintf('No element found for %1$s', $matcher));
    }
}
