<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasDescendantMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        $childContext = new MatchContext(
            driver: $context->driver,
            isNegated: $context->isNegated,
            // Since we are waiting ourselves, we don't want the child matchers to wait as well.
            options: $context->options->toInstantOptions(),
        );

        return $this->waitForMatch($context, fn () => $context->isNegated
            ? $this->matchElementsWithoutDescendants($container, $childContext)
            : $this->matchElementsWithDescendants($container, $childContext));
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithDescendants(MatchResult $container, MatchContext $context): array
    {
        $descendantMatch = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($descendantMatch->all() as $descendant) {
            /** @var WebDriverElement[] $ancestors */
            $ancestors = array_reverse($descendant->findElements(WebDriverBy::xpath('./ancestor::*')));

            if (!count($ancestors)) {
                continue;
            }

            foreach ($ancestors as $ancestor) {
                $elements[$ancestor->getID()] = $ancestor;
            }
        }

        return $elements;
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithoutDescendants(MatchResult $container, MatchContext $context): array
    {
        // Find any elements that are the descendants we want to negate.
        $descendantsAndAncestors = $this->matchElementsWithDescendants($container, new MatchContext(
            driver: $context->driver,
            isNegated: false,
            options: $context->options,
        ));

        // Find all elements that are not those.
        $elements = [];

        foreach ($container->all() as $containerElement) {
            // TODO This is probably a bad idea on dom heavy pages
            $potentiallyNotDescendants = $containerElement->findElements(WebDriverBy::cssSelector('*'));

            foreach ($potentiallyNotDescendants as $element) {
                if (!isset($descendantsAndAncestors[$element->getID()])) {
                    $elements[$element->getID()] = $element;
                }
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('descendant(%1$s)', $this->matcher);
    }
}
