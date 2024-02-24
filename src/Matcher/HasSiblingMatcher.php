<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\Traits\NegatesUsingPositiveMatch;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    use NegatesUsingPositiveMatch;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
                ? $this->matchElementsWithoutMatch($container, $context)
                : $this->matchElementsWithMatch($container, $context),
        );
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithMatch(MatchResult $container, EspressoContext $context): array
    {
        $siblingResult = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($siblingResult->all() as $sibling) {
            $adjacentChildren = $sibling->findElements(
                WebDriverBy::xpath('following-sibling::* | preceding-sibling::*'),
            );

            foreach ($adjacentChildren as $child) {
                $elements[$child->getID()] = $child;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasSibling(%1$s)', $this->matcher);
    }
}
