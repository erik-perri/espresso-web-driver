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
        // TODO Figure out a better path that works for all languages
        //      XPath's newer lower-case() or matches() are not supported
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[contains(%1$s, "%2$s")]',
                'translate(text(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                mb_strtolower($this->text),
            )),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsNotContainingText(WebDriverElement $container): array
    {
        // TODO Figure out a better path that works for all languages
        //      XPath's newer lower-case() or matches() are not supported
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[not(contains(%1$s, "%2$s"))]',
                'translate(text(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                mb_strtolower($this->text),
            )),
        );
    }

    public function __toString(): string
    {
        return sprintf('withTextContaining(%1$s)', $this->text);
    }
}
