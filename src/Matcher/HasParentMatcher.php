<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class HasParentMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $parentMatch = $this->matcher->match($container, $context);

        $matchesByParent = [];

        foreach ($parentMatch as $parent) {
            $matchesByParent[] = $parent->findElements(WebDriverBy::xpath('./child::*'));
        }

        return array_merge(...$matchesByParent);
    }

    public function __toString(): string
    {
        return sprintf('hasParent(%1$s)', $this->matcher);
    }
}
