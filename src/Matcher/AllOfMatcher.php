<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverElement;

final readonly class AllOfMatcher implements MatcherInterface
{
    use HasAutomaticWait;

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
        return $this->waitForMatch($context, fn () => $this->matchElements($container, $context));
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElements(MatchResult $container, EspressoContext $context): array
    {
        $childContext = new EspressoContext(
            driver: $context->driver,
            // Since we are waiting ourselves, we don't want the child matchers to wait as well.
            options: $context->options->toInstantOptions(),
            isNegated: $context->isNegated,
        );

        $resultsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $resultsByMatcher[] = $matcher->match($container, $childContext);
        }

        $firstResult = array_shift($resultsByMatcher);

        if ($firstResult === null) {
            return [];
        }

        $commonResults = $firstResult->all();

        foreach ($resultsByMatcher as $result) {
            $commonResults = array_uintersect($commonResults, $result->all(), $this->compareElements(...));
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
