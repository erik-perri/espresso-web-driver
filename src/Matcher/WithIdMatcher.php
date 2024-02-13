<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithIdMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $id)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchElementsWithoutId($container->single())
                : $this->matchElementsWithId($container->single())
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithId(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getAttribute('id') === $this->id) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::id($this->id)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutId(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getAttribute('id') !== $this->id) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf(':not([id="%1$s"])', $this->id))),
        );
    }

    public function __toString(): string
    {
        return sprintf('id="%1$s"', $this->id);
    }
}
