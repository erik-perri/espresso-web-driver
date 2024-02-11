<?php

declare(strict_types=1);

namespace EspressoWebDriver\Assertion;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class MatchesAssertion implements AssertionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function assert(WebDriverElement $container, EspressoContext $context): bool
    {
        $matches = $this->matcher->match($container, $context);

        $filteredToContainer = array_filter(
            $matches,
            fn (WebDriverElement $element) => $element->getID() === $container->getID(),
        );

        return count($filteredToContainer) > 0;
    }

    public function __toString(): string
    {
        return sprintf('matches(%1$s)', $this->matcher);
    }
}
