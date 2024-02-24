<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverElement;

final readonly class AnyOfMatcher implements MatcherInterface
{
    /**
     * @var MatcherInterface[]
     */
    private array $matchers;

    public function __construct(MatcherInterface ...$matchers)
    {
        $this->matchers = $matchers;
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $this->matchElements($container, $context),
        );
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElements(MatchResult $container, EspressoContext $context): array
    {
        $resultsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $resultsByMatcher[] = $matcher->match($container, $context);
        }

        return array_merge(
            ...array_map(fn (MatchResult $result) => $result->all(), $resultsByMatcher),
        );
    }

    public function __toString(): string
    {
        return sprintf('anyOf(%1$s)', implode('; ', $this->matchers));
    }
}
