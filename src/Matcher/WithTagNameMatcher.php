<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTagNameMatcher implements MatcherInterface
{
    public function __construct(private string $tagName)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $context->isNegated
            ? $this->matchElementsWithoutTagName($container->single())
            : $this->matchElementsWithTagName($container->single());
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithTagName(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::%1$s', $this->tagName)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutTagName(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[not(self::%1$s)]', $this->tagName)),
        );
    }

    public function __toString(): string
    {
        return sprintf('withTagName(%1$s)', $this->tagName);
    }
}
