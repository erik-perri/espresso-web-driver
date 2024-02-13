<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithClassMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $class)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchElementsWithoutClass($container->single())
                : $this->matchElementsWithClass($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithClass(WebDriverElement $container): array
    {
        $elements = [];

        if ($this->hasClass($container)) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::className($this->class)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutClass(WebDriverElement $container): array
    {
        $elements = [];

        if (!$this->hasClass($container)) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::cssSelector(sprintf(':not(.%1$s)', $this->class))),
        );
    }

    private function hasClass(WebDriverElement $element): bool
    {
        $classes = $element->getAttribute('class');

        if (!$classes) {
            return false;
        }

        $classNames = array_map('trim', explode(' ', $classes));

        return in_array($this->class, $classNames, true);
    }

    public function __toString(): string
    {
        return sprintf('class="%1$s"', $this->class);
    }
}
