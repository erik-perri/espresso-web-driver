<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;

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

    public function match(MatchResult $container, EspressoContext $context): array
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
