<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoParentException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoParentException
     */
    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        $childContext = new MatchContext(
            driver: $context->driver,
            isNegated: $context->isNegated,
            // Since we are waiting ourselves, we don't want the child matchers to wait as well.
            options: $context->options->toInstantOptions(),
        );

        return $this->waitForMatch($context, fn () => $context->isNegated
            ? $this->matchElementsWithoutSiblings($container, $childContext)
            : $this->matchElementsWithSiblings($container, $childContext));
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException|NoParentException
     */
    private function matchElementsWithSiblings(MatchResult $container, MatchContext $context): array
    {
        $siblingResult = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($siblingResult->all() as $sibling) {
            $adjacentChildren = $sibling->findElements(
                WebDriverBy::xpath('following-sibling::* | preceding-sibling::*'),
            );

            foreach ($adjacentChildren as $child) {
                $elements[$child->getID()] = $child;
            }
        }

        return $elements;
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException|NoParentException
     */
    private function matchElementsWithoutSiblings(MatchResult $container, MatchContext $context): array
    {
        // Find any elements that are the siblings we want to negate.
        $elementsWithSiblings = $this->matchElementsWithSiblings($container, new MatchContext(
            driver: $context->driver,
            isNegated: false,
            options: $context->options,
        ));

        // Find all elements that are not those.
        $elements = [];

        foreach ($container->all() as $containerElement) {
            // TODO This is probably a bad idea on dom heavy pages
            $potentiallyNotSiblings = $containerElement->findElements(WebDriverBy::cssSelector('*'));

            foreach ($potentiallyNotSiblings as $element) {
                if (!isset($elementsWithSiblings[$element->getID()])) {
                    $elements[$element->getID()] = $element;
                }
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('hasSibling(%1$s)', $this->matcher);
    }
}
