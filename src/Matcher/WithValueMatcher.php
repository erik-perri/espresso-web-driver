<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithValueMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $text)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
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

        if ($container->getAttribute('value') === $this->text) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf('[value="%1$s"]', $this->text))),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutValue(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getAttribute('value') !== $this->text) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf(':not([value="%1$s"])', $this->text))),
        );
    }

    public function __toString(): string
    {
        return sprintf('value="%1$s"', $this->text);
    }
}
