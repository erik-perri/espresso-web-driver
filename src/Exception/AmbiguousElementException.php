<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;

class AmbiguousElementException extends EspressoWebDriverException
{
    public function __construct(int $count, MatcherInterface $matcher)
    {
        parent::__construct(sprintf('%1$s elements found for %2$s', number_format($count), $matcher));
    }
}
