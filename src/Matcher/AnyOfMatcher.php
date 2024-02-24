<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverBy;
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
        $elements = [];

        foreach ($this->matchers as $matcher) {
            $results = $matcher->match($container, $context);

            foreach ($results->all() as $element) {
                $elements[$element->getID()] = $element;
            }
        }

        return $elements;
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

    public function __toString(): string
    {
        return sprintf('anyOf(%1$s)', implode('; ', $this->matchers));
    }
}
