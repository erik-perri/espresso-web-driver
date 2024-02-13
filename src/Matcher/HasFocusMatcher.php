<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasFocusMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch($context, fn () => $this->matchElements($container->single(), $context));
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElements(WebDriverElement $container, MatchContext $context): array
    {
        try {
            $parent = $container->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            return [];
        }

        return $parent->findElements(WebDriverBy::cssSelector(
            $context->isNegated ? ':not(:focus)' : ':focus',
        ));
    }

    public function __toString(): string
    {
        return 'focused';
    }
}
