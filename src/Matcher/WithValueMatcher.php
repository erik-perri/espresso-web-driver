<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithValueMatcher implements MatcherInterface
{
    public function __construct(private string $value)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
                ? $this->matchElementsWithoutValue($container->single())
                : $this->matchElementsWithValue($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithValue(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getAttribute('value') === $this->value) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf('[value="%1$s"]', $this->value))),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutValue(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getAttribute('value') !== $this->value) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf(':not([value="%1$s"])', $this->value))),
        );
    }

    public function __toString(): string
    {
        return sprintf('withValue(%1$s)', $this->value);
    }
}
