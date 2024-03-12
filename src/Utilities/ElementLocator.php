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
        $parent = $element;

        while (!$this->isScreenReaderElement($parent) && $parent->getID() !== $container?->getID()) {
            try {
                $parent = $parent->findElement(WebDriverBy::xpath('..'));
            } catch (NoSuchElementException) {
                return $element;
            }
        }

        return $parent;
    }

    private function isScreenReaderElement(WebDriverElement $target): bool
    {
        $classList = $target->getAttribute('class');

        if (!$classList) {
            return true;
        }

        $classes = explode(' ', $classList);

        return empty(array_intersect($this->invisibleClasses, $classes));
    }
}
