<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;

class NoParentException extends EspressoWebDriverException
{
    public function __construct(MatcherInterface $matcher, string $element)
    {
        parent::__construct(sprintf(
            'Unable to locate a parent while checking %1$s for %2$s',
            $element,
            $matcher,
        ));
    }
}
