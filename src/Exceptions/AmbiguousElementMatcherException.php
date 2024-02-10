<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exceptions;

use EspressoWebDriver\Matcher\MatcherInterface;

class AmbiguousElementMatcherException extends EspressoWebDriverException
{
    public function __construct(int $count, MatcherInterface $matcher)
    {
        parent::__construct(sprintf('%1$s elements found for %2$s', number_format($count), $matcher));
    }
}
