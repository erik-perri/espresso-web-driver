<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextContainingMatcher implements MatcherInterface
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
                ? $this->matchElementsNotContainingText($container->single())
                : $this->matchElementsContainingText($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsContainingText(WebDriverElement $container): array
    {
        $elements = [];

        if (mb_stripos($container->getText(), $this->text) !== false) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            // TODO Figure out a better path that works for all languages
            //      XPath's newer lower-case() or matches() are not supported
            $container->findElements(WebDriverBy::xpath(sprintf(
                './/*[contains(%1$s, "%2$s")]',
                'translate(text(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                $this->text,
            ))),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsNotContainingText(WebDriverElement $container): array
    {
        $elements = [];

        if (mb_stripos($container->getText(), $this->text) === false) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            // TODO Figure out a better path that works for all languages
            //      XPath's newer lower-case() or matches() are not supported
            $container->findElements(WebDriverBy::xpath(sprintf(
                './/*[not(contains(%1$s, "%2$s"))]',
                'translate(text(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                $this->text,
            ))),
        );
    }

    public function __toString(): string
    {
        return sprintf('textContaining="%1$s"', $this->text);
    }
}
