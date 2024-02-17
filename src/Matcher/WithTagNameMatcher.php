<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTagNameMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $tagName)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchElementsWithoutTagName($container->single())
                : $this->matchElementsWithTagName($container->single())
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithTagName(WebDriverElement $container): array
    {
        $elements = [];

        if (strcasecmp($container->getTagName(), $this->tagName) === 0) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::tagName($this->tagName)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutTagName(WebDriverElement $container): array
    {
        $elements = [];

        if (strcasecmp($container->getTagName(), $this->tagName) !== 0) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf(':not(%1$s)', $this->tagName))),
        );
    }

    public function __toString(): string
    {
        return sprintf('withTagName(%1$s)', $this->tagName);
    }
}
