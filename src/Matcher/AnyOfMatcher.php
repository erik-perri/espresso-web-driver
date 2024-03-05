<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\Traits\NegatesUsingPositiveMatch;
use Facebook\WebDriver\WebDriverElement;

final readonly class AnyOfMatcher implements MatcherInterface
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
        $elements = [];

        foreach ($this->matchers as $matcher) {
            $results = $matcher->match($container, $context);

            foreach ($results as $element) {
                $elements[$element->getID()] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('anyOf(%1$s)', implode('; ', $this->matchers));
    }
}
