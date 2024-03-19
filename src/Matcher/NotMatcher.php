<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Processor\MatchResult;
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

        $elementsMatchingById = [];

        foreach ($this->matcher->match($container, $context) as $element) {
            $elementsMatchingById[$element->getID()] = $element;
        }

        // TODO This is probably a bad idea on dom heavy pages
        $potentiallyNotMatching = $container->single()->findElements(WebDriverBy::cssSelector('*'));

        return array_filter(
            $potentiallyNotMatching,
            fn ($element) => !isset($elementsMatchingById[$element->getID()]),
        );
    }

    public function __toString(): string
    {
        return sprintf('not(%1$s)', $this->matcher);
    }
}
