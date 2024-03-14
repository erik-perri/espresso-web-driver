<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\WebDriverBy;

final readonly class HasDescendantMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $descendantMatch = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($descendantMatch as $descendant) {
            $ancestors = $descendant->findElements(WebDriverBy::xpath('./ancestor::*'));

            foreach ($ancestors as $ancestor) {
                $elements[] = $ancestor;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasDescendant(%1$s)', $this->matcher);
    }
}
