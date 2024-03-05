<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithClassMatcher implements MatcherInterface
{
    public function __construct(private string $class)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $context->isNegated
            ? $this->matchElementsWithoutClass($container->single())
            : $this->matchElementsWithClass($container->single());
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithClass(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[contains(concat(" ", normalize-space(@class), " "), " %1$s ")]',
                $this->class,
            )),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutClass(WebDriverElement $container): array
    {
        return $container->findElements(
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
