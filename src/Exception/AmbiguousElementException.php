<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Utilities\ElementPathLogger;
use Facebook\WebDriver\WebDriverElement;

class AmbiguousElementException extends EspressoWebDriverException
{
    /**
     * @param  WebDriverElement[]  $elements
     */
    public function __construct(array $elements, MatcherInterface $matcher)
    {
        $logger = new ElementPathLogger();

        $totalElements = count($elements);

        $renderedElements = array_map(
            fn (WebDriverElement $element) => $logger->describe($element),
            array_slice($elements, 0, 3),
        );

        if ($totalElements > count($renderedElements)) {
            $renderedElements[] = '...';
        }

        parent::__construct(sprintf(
            '%1$s elements found for %2$s%3$s',
            number_format($totalElements),
            $matcher,
            "\n".implode("\n", $renderedElements),
        ));
    }
}
