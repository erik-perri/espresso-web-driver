<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithIdMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $id)
    {
        //
    }

    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchElementsWithoutId($container->single())
                : $this->matchElementsWithId($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithId(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[@id="%1$s"]', $this->id)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutId(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(sprintf('descendant-or-self::*[not(@id="%1$s")]', $this->id)),
        );
    }

    public function __toString(): string
    {
        return sprintf('withId(%1$s)', $this->id);
    }
}
