<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\WebDriverBy;

final readonly class NotMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        if ($this->matcher instanceof NegativeMatcherInterface) {
            return $this->matcher->matchNegative($container, $context);
        }

        $elementsMatching = [];

        foreach ($this->matcher->match($container, $context) as $element) {
            $elementsMatching[$element->getID()] = $element;
        }

        $elementsNotMatching = [];

        foreach ($container->all() as $containerElement) {
            // TODO This is probably a bad idea on dom heavy pages
            $potentiallyNotMatching = $containerElement->findElements(WebDriverBy::cssSelector('*'));

            foreach ($potentiallyNotMatching as $element) {
                if (!isset($elementsMatching[$element->getID()])) {
                    $elementsNotMatching[$element->getID()] = $element;
                }
            }
        }

        return $elementsNotMatching;
    }

    public function __toString(): string
    {
        return sprintf('not(%1$s)', $this->matcher);
    }
}
