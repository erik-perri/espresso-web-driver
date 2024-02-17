<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use EspressoWebDriver\Utilities\TextNormalizer;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithLabelMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    private string $normalizedText;

    public function __construct(string $text)
    {
        $this->normalizedText = (new TextNormalizer())->normalize($text);
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch(
            $context,
            fn () => $context->isNegated
                ? $this->matchElementsWithoutLabel($container->single())
                : $this->matchElementsWithLabel($container->single()),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithLabel(WebDriverElement $container): array
    {
        $elements = [];

        $labels = $container->findElements(
            WebDriverBy::xpath(sprintf(
                'descendant-or-self::label[normalize-space(text())="%s"]',
                $this->normalizedText,
            )),
        );

        foreach ($labels as $label) {
            $for = $label->getAttribute('for');

            // If the label has a "for" attribute we can use it to find the input, otherwise we need to look for an
            // input child.
            if ($for !== null) {
                $elements[] = $this->matchElementsWithId($container, $for);
            } else {
                $elements[] = $this->matchFirstFormElement($label);
            }
        }

        return array_merge(...$elements);
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElementsWithoutLabel(WebDriverElement $container): array
    {
        $labelledElements = $this->matchElementsWithLabel($container);

        $allElements = $container->findElements(
            WebDriverBy::xpath(
                'descendant::*[(self::input and not(@type="hidden")) or self::select or self::textarea]',
            ),
        );

        return array_udiff(
            $allElements,
            $labelledElements,
            function (WebDriverElement $elementA, WebDriverElement $elementB): int {
                return $elementA->getID() <=> $elementB->getID();
            },
        );
    }

    /**
     * @return array<string, WebDriverElement>
     */
    private function matchElementsWithId(WebDriverElement $container, string $for): array
    {
        try {
            $input = $container->findElement(WebDriverBy::id($for));

            return [$input->getID() => $input];
        } catch (NoSuchElementException) {
            //
        }

        return [];
    }

    /**
     * @return array<string, WebDriverElement>
     */
    private function matchFirstFormElement(WebDriverElement $label): array
    {
        $inputs = $label->findElements(
            WebDriverBy::xpath(
                'descendant::*[self::button or (self::input and not(@type="hidden")) or self::select or self::textarea]',
            ),
        );

        if (!count($inputs)) {
            return [];
        }

        $input = reset($inputs);

        return [$input->getID() => $input];
    }

    public function __toString(): string
    {
        return sprintf('withLabel(%1$s)', $this->normalizedText);
    }
}