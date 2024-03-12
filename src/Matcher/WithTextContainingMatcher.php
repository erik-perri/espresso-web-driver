<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Utilities\TextNormalizer;
use EspressoWebDriver\Utilities\XPathStringWrapper;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithTextContainingMatcher implements MatcherInterface, NegativeMatcherInterface
{
    private string $normalizedText;

    private string $wrappedText;

    public function __construct(string $text)
    {
        $this->normalizedText = (new TextNormalizer())->normalize($text);
        $this->wrappedText = (new XPathStringWrapper())->wrap(mb_strtolower($this->normalizedText));
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        // TODO Figure out a better path that works for all languages
        //      XPath's newer lower-case() or matches() are not supported
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[text()[contains(%1$s, %2$s)]]',
                'translate(normalize-space(.), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                $this->wrappedText,
            )),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        // TODO Figure out a better path that works for all languages
        //      XPath's newer lower-case() or matches() are not supported
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[text()[not(contains(%1$s, %2$s))]]',
                'translate(normalize-space(.), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                $this->wrappedText,
            )),
        );
    }

    public function __toString(): string
    {
        return sprintf('withTextContaining(%1$s)', $this->normalizedText);
    }
}
