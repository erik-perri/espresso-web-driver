<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextMatcher implements MatcherInterface
{
    public function __construct(private string $text)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
                ? $this->matchElementsWithoutText($container->single())
                : $this->matchElementsWithText($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithText(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[normalize-space(text())="%1$s"]', $this->text)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutText(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[not(normalize-space(text())="%1$s")]', $this->text)),
        );
    }

    public function __toString(): string
    {
        return sprintf('withText(%1$s)', $this->text);
    }
}
