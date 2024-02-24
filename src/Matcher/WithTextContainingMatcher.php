<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Utilities\TextNormalizer;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextContainingMatcher implements MatcherInterface
{
    private string $normalizedText;

    public function __construct(string $text)
    {
        $this->normalizedText = (new TextNormalizer())->normalize($text);
    }

    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
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
                'translate(normalize-space(text()), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                mb_strtolower($this->normalizedText),
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
                'translate(normalize-space(text()), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                mb_strtolower($this->normalizedText),
            )),
        );
    }

    public function __toString(): string
    {
        return sprintf('withTextContaining(%1$s)', $this->normalizedText);
    }
}
