<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Processor\MatchResult;
use Facebook\WebDriver\WebDriverBy;

final readonly class HasAncestorMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $ancestorMatch = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($ancestorMatch as $ancestor) {
            $descendants = $ancestor->findElements(WebDriverBy::xpath('.//*'));

            foreach ($descendants as $descendant) {
                $elements[] = $descendant;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasAncestor(%1$s)', $this->matcher);
    }
}
