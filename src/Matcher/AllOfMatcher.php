<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\WebDriverElement;

final readonly class AllOfMatcher implements MatcherInterface
{
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

        return $commonResults;
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
