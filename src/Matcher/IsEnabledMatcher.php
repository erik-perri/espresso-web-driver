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
        $elements = [];

        if (!$container->isEnabled()) {
            $elements[] = $container;
        }

        $possibleElements = $container->findElements(
            WebDriverBy::cssSelector('button, fieldset, optgroup, option, select, textarea, input'),
        );

        foreach ($possibleElements as $element) {
            if (!$element->isEnabled()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchEnabledElements(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->isEnabled()) {
            $elements[] = $container;
        }

        $possibleElements = $container->findElements(
            WebDriverBy::cssSelector('button, fieldset, optgroup, option, select, textarea, input'),
        );

        foreach ($possibleElements as $element) {
            if ($element->isEnabled()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return 'enabled';
    }
}
