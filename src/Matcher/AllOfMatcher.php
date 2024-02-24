<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverBy;
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

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
                ? $this->matchElementsNotMatching($container, $context)
                : $this->matchElementsMatching($container, $context),
        );
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsMatching(MatchResult $container, EspressoContext $context): array
    {
        $resultsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $resultsByMatcher[] = $matcher->match($container, $context);
        }

        $firstResult = array_shift($resultsByMatcher);

        if ($firstResult === null) {
            return [];
        }

        $commonResults = $firstResult->all();

        foreach ($resultsByMatcher as $result) {
            $commonResults = array_uintersect($commonResults, $result->all(), $this->compareElements(...));
        }

        $commonResultsById = [];

        foreach ($commonResults as $element) {
            $commonResultsById[$element->getID()] = $element;
        }

        return $commonResultsById;
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsNotMatching(MatchResult $container, EspressoContext $context): array
    {
        $elementsMatching = $this->matchElementsMatching($container, new EspressoContext(
            driver: $context->driver,
            options: $context->options,
        ));

        $elements = [];

        foreach ($container->all() as $containerElement) {
            // TODO This is probably a bad idea on dom heavy pages
            $potentiallyNotMatching = $containerElement->findElements(WebDriverBy::cssSelector('*'));

            foreach ($potentiallyNotMatching as $element) {
                if (!isset($elementsMatching[$element->getID()])) {
                    $elements[$element->getID()] = $element;
                }
            }
        }

        return $elements;
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
