<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\Traits\NegatesUsingPositiveMatch;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasDescendantMatcher implements MatcherInterface
{
    use NegatesUsingPositiveMatch;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

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
        $descendantMatch = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($descendantMatch->all() as $descendant) {
            /** @var WebDriverElement[] $ancestors */
            $ancestors = array_reverse($descendant->findElements(WebDriverBy::xpath('./ancestor::*')));

            foreach ($ancestors as $ancestor) {
                $elements[$ancestor->getID()] = $ancestor;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasDescendant(%1$s)', $this->matcher);
    }
}
