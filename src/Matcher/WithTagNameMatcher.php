<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithTagNameMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function __construct(private string $tagName)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $container->single()->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::%1$s', $this->tagName)),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return $container->single()->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[not(self::%1$s)]', $this->tagName)),
        );
    }

    public function __toString(): string
    {
        return sprintf('withTagName(%1$s)', $this->tagName);
    }
}
