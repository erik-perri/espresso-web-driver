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
        $match = $this->matcher->match($root, $options);

        return reset($match) === $root;
    }

    public function __toString(): string
    {
        return sprintf('matches(%s)', $this->matcher);
    }
}
