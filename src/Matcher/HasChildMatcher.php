<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class HasChildMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $childMatch = $this->matcher->match($container, $context);

        $matchesByChild = [];

        foreach ($childMatch as $child) {
            $matchesByChild[] = $child->findElements(WebDriverBy::xpath('./parent::*'));
        }

        return array_merge(...$matchesByChild);
    }

    public function __toString(): string
    {
        return sprintf('hasChild(%1$s)', $this->matcher);
    }
}
