<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\Traits\NegatesUsingPositiveMatch;
use Facebook\WebDriver\WebDriverElement;

final readonly class AllOfMatcher implements MatcherInterface
{
    use NegatesUsingPositiveMatch;

    /**
     * @var MatcherInterface[]
     */
    private array $matchers;

    public function __construct(MatcherInterface ...$matchers)
    {
        $this->matchers = $matchers;
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $context->isNegated
            ? $this->matchElementsWithoutMatch($container, $context)
            : $this->matchElementsWithMatch($container, $context);
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithMatch(MatchResult $container, EspressoContext $context): array
    {
        $resultsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $resultsByMatcher[] = $matcher->match($container, $context);
        }

        $commonResults = array_shift($resultsByMatcher);

        if ($commonResults === null) {
            return [];
        }

        foreach ($resultsByMatcher as $result) {
            $commonResults = array_uintersect($commonResults, $result, $this->compareElements(...));
        }

        $commonResultsById = [];

        foreach ($commonResults as $element) {
            $commonResultsById[$element->getID()] = $element;
        }

        return $commonResultsById;
    }

    private function compareElements(WebDriverElement $a, WebDriverElement $b): int
    {
        return strcmp($a->getID(), $b->getID());
    }

    public function __toString(): string
    {
        return sprintf('allOf(%1$s)', implode('; ', $this->matchers));
    }
}
