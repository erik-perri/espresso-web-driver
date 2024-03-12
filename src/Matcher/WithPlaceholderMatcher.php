<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Utilities\TextNormalizer;
use EspressoWebDriver\Utilities\XPathStringWrapper;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithPlaceholderMatcher implements MatcherInterface, NegativeMatcherInterface
{
    private string $normalizedText;

    private string $wrappedText;

    public function __construct(string $text)
    {
        $this->normalizedText = (new TextNormalizer)->normalize($text);
        $this->wrappedText = (new XPathStringWrapper)->wrap($this->normalizedText);
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[normalize-space(@placeholder)=%1$s]',
                $this->wrappedText,
            )),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[not(normalize-space(@placeholder)=%1$s)]',
                $this->wrappedText,
            )),
        );
    }

    public function __toString(): string
    {
        return sprintf('withPlaceholder(%1$s)', $this->normalizedText);
    }
}
