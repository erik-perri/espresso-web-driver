<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch($context, fn () => $this->matchElements($container, $context));
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    private function matchElements(MatchResult $container, MatchContext $context): array
    {
        $childContext = new MatchContext(
            driver: $context->driver,
            isNegated: false,
            // Since we are waiting ourselves, we don't want the child matchers to wait as well.
            options: $context->options->toInstantOptions(),
        );

        $siblingResult = $this->matcher->match($container, $childContext);

        $elements = [];

        foreach ($siblingResult->all() as $sibling) {
            try {
                $parent = $sibling->findElement(WebDriverBy::xpath('./parent::*'));

                $adjacentChildren = $parent->findElements(WebDriverBy::xpath('./*'));

                foreach ($adjacentChildren as $child) {
                    if ($child->getID() !== $sibling->getID()) {
                        $elements[] = $child;
                    }
                }
            } catch (NoSuchElementException) {
                // If we couldn't find the parent, we can't find the siblings.
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('sibling(%1$s)', $this->matcher);
    }
}
