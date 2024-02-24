<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsEnabledMatcher implements MatcherInterface
{
    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function match(MatchResult $container, EspressoContext $context): MatchResult
    {
        return new MatchResult(
            matcher: $this,
            result: $context->isNegated
                ? $this->matchDisabledElements($container->single())
                : $this->matchEnabledElements($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchDisabledElements(WebDriverElement $container): array
    {
        $disabledElements = $container->findElements(
            WebDriverBy::xpath('descendant-or-self::*[@disabled]'),
        );

        $descendantsOfDisabledFieldSets = $container->findElements(
            WebDriverBy::xpath('descendant-or-self::fieldset[@disabled]//*'),
        );

        $elements = [];

        foreach (array_merge($disabledElements, $descendantsOfDisabledFieldSets) as $element) {
            $elements[$element->getID()] = $element;
        }

        return $elements;
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchEnabledElements(WebDriverElement $container): array
    {
        $allElements = $container->findElements(
            WebDriverBy::xpath(
                'descendant-or-self::*['
                .'not(@disabled) and '
                .'(self::button or self::fieldset or self::input or self::optgroup or self::option or self::select or self::textarea)'
                .']',
            ),
        );

        $enabledElements = [];

        foreach ($allElements as $element) {
            $disabledFieldsetAncestors = $element->findElements(
                WebDriverBy::xpath('.//ancestor::fieldset[@disabled]'),
            );
            if (count($disabledFieldsetAncestors) > 0) {
                continue;
            }

            $enabledElements[] = $element;
        }

        return $enabledElements;
    }

    public function __toString(): string
    {
        return 'isEnabled';
    }
}
