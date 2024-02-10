<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class MatchesAssertion implements AssertionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function assert(WebDriverElement $root, EspressoOptions $options): bool
    {
        $matches = $this->matcher->match($root, $options);

        $filteredToRoot = array_filter(
            $matches,
            fn (WebDriverElement $element) => $element->getID() === $root->getID(),
        );

        return count($filteredToRoot) > 0;
    }

    public function __toString(): string
    {
        return sprintf('matches(%s)', $this->matcher);
    }
}
