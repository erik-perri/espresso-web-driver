<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithTextMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function __construct(private string $text)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[normalize-space(text())="%1$s"]', $this->text)),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[not(normalize-space(text())="%1$s")]', $this->text)),
        );
    }

    public function __toString(): string
    {
        return sprintf('withText(%1$s)', $this->text);
    }
}
