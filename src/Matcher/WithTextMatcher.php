<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextMatcher implements MatcherInterface
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
                ? $this->matchElementsWithoutText($container->single())
                : $this->matchElementsWithText($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithText(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getText() === $this->text) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::xpath(sprintf('.//*[text()="%1$s"]', $this->text))),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutText(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getText() !== $this->text) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::xpath(sprintf('.//*[not(text()="%1$s")]', $this->text))),
        );
    }

    public function __toString(): string
    {
        return sprintf('withText(%1$s)', $this->text);
    }
}
