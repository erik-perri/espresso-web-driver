<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
                ? $this->matchElementsWithoutSiblings($container, $context)
                : $this->matchElementsWithSiblings($container, $context),
        );
    }

    /**
     * @return array<string, WebDriverElement>
     *
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithSiblings(MatchResult $container, EspressoContext $context): array
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
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function matchElementsWithoutSiblings(MatchResult $container, EspressoContext $context): array
    {
        // Find any elements that are the siblings we want to negate.
        $elementsWithSiblings = $this->matchElementsWithSiblings($container, new EspressoContext(
            driver: $context->driver,
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
