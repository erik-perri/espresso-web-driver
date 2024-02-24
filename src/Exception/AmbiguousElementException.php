<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

class AmbiguousElementException extends EspressoWebDriverException
{
    /**
     * @param  WebDriverElement[]  $elements
     */
    public function __construct(array $elements, MatcherInterface $matcher)
    {
        $totalElements = count($elements);

        parent::__construct(sprintf(
            '%1$s %2$s found for %3$s',
            number_format($totalElements),
            $totalElements === 1 ? 'element' : 'elements',
            $matcher,
        ));
    }
}
