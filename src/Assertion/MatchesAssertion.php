<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class MatchesAssertion implements AssertionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function assert(WebDriverElement $root): bool
    {
        $match = $this->matcher->match($root);

        return reset($match) === $root;
    }
}
