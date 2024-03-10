<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;

final readonly class CommonAncestorOfMatcher implements MatcherInterface
{
    /**
     * @var MatcherInterface[]
     */
    private array $matchers;

    public function __construct(MatcherInterface ...$matchers)
    {
        $this->matchers = array_values($matchers);
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): array
    {
        $result = (new AllOfMatcher(...array_map(
            fn (MatcherInterface $matcher) => new HasDescendantMatcher($matcher),
            $this->matchers,
        )))->match($container, $context);

        if (empty($result)) {
            return $result;
        }

        // The last match should be the deepest common ancestor.
        $lastMatch = end($result);

        return [$lastMatch];
    }

    public function __toString(): string
    {
        return sprintf('commonAncestorOf(%1$s)', implode('; ', $this->matchers));
    }
}
