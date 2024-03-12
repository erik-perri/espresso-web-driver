<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Utilities\XPathStringWrapper;
use Facebook\WebDriver\WebDriverBy;

final readonly class WithTextMatcher implements MatcherInterface, NegativeMatcherInterface
{
    private string $wrappedText;

    public function __construct(private string $text)
    {
        $this->wrappedText = (new XPathStringWrapper())->wrap($this->text);
    }

    public function match(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[text()[normalize-space(.)=%1$s]]',
                $this->wrappedText,
            )),
        );
    }

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::*[text()[not(normalize-space(.)=%1$s)]]',
                $this->wrappedText,
            )),
        );
    }

    public function __toString(): string
    {
        return sprintf('withText(%1$s)', $this->text);
    }
}
