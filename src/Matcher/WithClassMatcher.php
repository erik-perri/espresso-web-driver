<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithClassMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function __construct(private string $class)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $container->single()->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[contains(concat(" ", normalize-space(@class), " "), " %1$s ")]',
                $this->class,
            )),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return $container->single()->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[not(contains(concat(" ", normalize-space(@class), " "), " %1$s "))]',
                $this->class,
            )),
        );
    }

    public function __toString(): string
    {
        return sprintf('withClass(%1$s)', $this->class);
    }
}
