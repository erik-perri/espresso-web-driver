<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Processor\MatchResult;
use Facebook\WebDriver\WebDriverBy;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $siblingResult = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($siblingResult as $sibling) {
            $adjacentChildren = $sibling->findElements(
                WebDriverBy::xpath('following-sibling::* | preceding-sibling::*'),
            );

            foreach ($adjacentChildren as $child) {
                $elements[] = $child;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasSibling(%1$s)', $this->matcher);
    }
}
