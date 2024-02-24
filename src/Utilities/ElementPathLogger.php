<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class ElementPathLogger implements ElementLoggerInterface
{
    public function describe(WebDriverElement $element): string
    {
        $hasId = $element->getAttribute('id') !== null;

        $tagNameOrIdSelector = $hasId
            ? sprintf('%1$s[@id="%2$s"]', $element->getTagName(), $element->getAttribute('id'))
            : $element->getTagName();

        $parentElement = null;

        try {
            $parentElement = $element->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            //
        }

        if (!$parentElement) {
            return $tagNameOrIdSelector;
        }

        $relatedSiblings = $parentElement->findElements(WebDriverBy::xpath('./'.$element->getTagName()));

        // If we're at the HTML or body level we only want to render the index if for some reason there are multiple
        // of them.
        if (count($relatedSiblings) === 1 && in_array($element->getTagName(), ['html', 'body'])) {
            return sprintf('%1$s/%2$s', $this->describe($parentElement), $tagNameOrIdSelector);
        }

        $elementIndex = $this->findElementIndex($relatedSiblings, $element);

        return sprintf(
            '%1$s/%2$s[%3$s]',
            $this->describe($parentElement),
            $tagNameOrIdSelector,
            $elementIndex === -1
                ? '?'
                : $elementIndex + 1,
        );
    }

    /**
     * @param  WebDriverElement[]  $elements
     */
    private function findElementIndex(array $elements, WebDriverElement $element): int
    {
        foreach (array_values($elements) as $index => $siblingElement) {
            if ($siblingElement->getID() === $element->getID()) {
                return $index;
            }
        }

        return -1;
    }
}
