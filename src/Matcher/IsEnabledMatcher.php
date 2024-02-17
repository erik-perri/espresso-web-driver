<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsEnabledMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchDisabledElements($container->single())
                : $this->matchEnabledElements($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchDisabledElements(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath('descendant-or-self::*[@disabled]'),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchEnabledElements(WebDriverElement $container): array
    {
        return $container->findElements(
            WebDriverBy::xpath(
                'descendant-or-self::*['
                .'not(@disabled) and '
                .'(self::button or self::fieldset or self::input or self::optgroup or self::option or self::select or self::textarea)'
                .']',
            ),
        );
    }

    public function __toString(): string
    {
        return 'isEnabled';
    }
}
