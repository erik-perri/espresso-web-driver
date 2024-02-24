<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Utilities\ElementAttributeLogger;
use Facebook\WebDriver\WebDriverElement;

class NoParentException extends EspressoWebDriverException
{
    public function __construct(MatcherInterface $matcher, WebDriverElement $element)
    {
        $logger = new ElementAttributeLogger();

        parent::__construct(sprintf(
            'Unable to locate a parent while checking %1$s for %2$s',
            $logger->describe($element),
            $matcher,
        ));
    }
}
