<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithIdMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function __construct(private string $id)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[@id="%1$s"]', $this->id)),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[not(@id="%1$s")]', $this->id)),
        );
    }

    public function __toString(): string
    {
        return sprintf('withId(%1$s)', $this->id);
    }
}
