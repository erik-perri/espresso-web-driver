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

    private const FORM_ELEMENT_SELECTOR = 'button, fieldset, input, optgroup, option, select, textarea';

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

        if (!$this->isEnabled($container)) {
            $elements[] = $container;
        }

        $possibleElements = $container->findElements(
            WebDriverBy::cssSelector(self::FORM_ELEMENT_SELECTOR),
        );

        foreach ($possibleElements as $element) {
            if (!$this->isEnabled($element)) {
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

        if ($this->isEnabled($container)) {
            $elements[] = $container;
        }

        $possibleElements = $container->findElements(
            WebDriverBy::cssSelector(self::FORM_ELEMENT_SELECTOR),
        );

        foreach ($possibleElements as $element) {
            if ($this->isEnabled($element)) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    private function isEnabled(WebDriverElement $element): bool
    {
        return $element->isEnabled()
            && !in_array($element->getAttribute('disabled'), ['true', 'disabled'], true);
    }

    public function __toString(): string
    {
        return 'isEnabled';
    }
}
