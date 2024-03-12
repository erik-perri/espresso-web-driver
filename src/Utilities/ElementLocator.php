<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class ElementLocator implements ElementLocatorInterface
{
    /**
     * @param  string[]  $invisibleClasses
     */
    public function __construct(private array $invisibleClasses = ['sr-only'])
    {
        //
    }

    public function findNonScreenReaderParent(
        WebDriverElement $element,
        ?WebDriverElement $container,
    ): WebDriverElement {
        if ($this->isScreenReaderElement($element)) {
            try {
                return $element->findElement(WebDriverBy::xpath('..'));
            } catch (NoSuchElementException) {
                //
            }
        }

        return $element;
    }

    private function isScreenReaderElement(WebDriverElement $target): bool
    {
        $classList = $target->getAttribute('class');

        if (!$classList) {
            return false;
        }

        $classes = explode(' ', $classList);

        return !empty(array_intersect($this->invisibleClasses, $classes));
    }
}
