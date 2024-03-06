<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Utilities\TextNormalizer;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithTextContainingMatcher implements MatcherInterface, NegativeMatcherInterface
{
    private string $normalizedText;

    public function __construct(string $text)
    {
        $this->normalizedText = (new TextNormalizer())->normalize($text);
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        // TODO Figure out a better path that works for all languages
        //      XPath's newer lower-case() or matches() are not supported
        return $container->single()->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[contains(%1$s, "%2$s")]',
                'translate(normalize-space(text()), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                mb_strtolower($this->normalizedText),
            )),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        // TODO Figure out a better path that works for all languages
        //      XPath's newer lower-case() or matches() are not supported
        return $container->single()->findElements(
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
