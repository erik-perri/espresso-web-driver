<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithValueMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function __construct(private string $value)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        $elements = [];

        foreach ($container->all() as $containerElement) {
            if ($containerElement->getAttribute('value') === $this->value) {
                $elements[] = $containerElement;
            }
        }

        $value = $this->cleanValueForCssSelector($this->value);

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf('[value="%1$s"]', $value))),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        $elements = [];

        foreach ($container->all() as $containerElement) {
            if ($containerElement->getAttribute('value') !== $this->value) {
                $elements[] = $containerElement;
            }
        }

        $value = $this->cleanValueForCssSelector($this->value);

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf(':not([value="%1$s"])', $value))),
        );
    }

    private function cleanValueForCssSelector(string $value): string
    {
        return str_replace(["\n", '"'], ['\n', '\"'], $value);
    }

    public function __toString(): string
    {
        $value = $this->cleanValueForCssSelector($this->value);

        return sprintf('withValue(%1$s)', $value);
    }
}
