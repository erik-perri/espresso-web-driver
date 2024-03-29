<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;

class NoMatchingElementException extends EspressoWebDriverException
{
    public function __construct(MatcherInterface $matcher)
    {
        parent::__construct(sprintf('no element found for %1$s', $matcher));
    }
}
