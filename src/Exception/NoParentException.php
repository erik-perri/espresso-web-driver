<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Utilities\ElementLogger;
use Facebook\WebDriver\WebDriverElement;

class NoParentException extends EspressoWebDriverException
{
    public function __construct(MatcherInterface $matcher, WebDriverElement $element)
    {
        parent::__construct(sprintf(
            'Unable to locate a parent while checking %1$s for %2$s',
            new ElementLogger($element),
            $matcher,
        ));
    }
}
