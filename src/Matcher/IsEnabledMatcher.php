<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverBy;

final readonly class IsEnabledMatcher implements MatcherInterface, NegativeMatcherInterface
{
    public function match(MatchResult $container, EspressoContext $context): array
    {
        $allElements = $container->single()->findElements(
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

    public function matchNegative(MatchResult $container, EspressoContext $context): array
    {
        $containerElement = $container->single();

        $disabledElements = $containerElement->findElements(
            WebDriverBy::xpath('descendant-or-self::*[@disabled]'),
        );

        $descendantsOfDisabledFieldSets = $containerElement->findElements(
            WebDriverBy::xpath('descendant-or-self::fieldset[@disabled]//*'),
        );

        return array_merge($disabledElements, $descendantsOfDisabledFieldSets);
    }

    public function __toString(): string
    {
        return 'isEnabled';
    }
}
