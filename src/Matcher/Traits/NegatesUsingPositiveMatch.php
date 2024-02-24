<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher\Traits;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatchResult;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

trait NegatesUsingPositiveMatch
{
    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    abstract private function matchElementsWithMatch(MatchResult $container, EspressoContext $context): array;

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithoutMatch(
        MatchResult $container,
        EspressoContext $context,
    ): array {
        $elementsMatching = $this->matchElementsWithMatch($container, new EspressoContext(
            driver: $context->driver,
            options: $context->options,
        ));

        $elements = [];

        foreach ($container->all() as $containerElement) {
            // TODO This is probably a bad idea on dom heavy pages
            $potentiallyNotMatching = $containerElement->findElements(WebDriverBy::cssSelector('*'));

            foreach ($potentiallyNotMatching as $element) {
                if (!isset($elementsMatching[$element->getID()])) {
                    $elements[$element->getID()] = $element;
                }
            }
        }

        return $elements;
    }
}
