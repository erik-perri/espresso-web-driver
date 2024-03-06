<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasChildMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $childMatch = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($childMatch as $child) {
            /** @var WebDriverElement[] $ancestors */
            $ancestors = array_reverse($child->findElements(WebDriverBy::xpath('./parent::*')));

            foreach ($ancestors as $ancestor) {
                $elements[$ancestor->getID()] = $ancestor;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasChild(%1$s)', $this->matcher);
    }
}
