<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithClassMatcher implements MatcherInterface
{
    public function __construct(private string $class)
    {
        //
    }

    public function match(WebDriverElement $root): array
    {
        $elements = [];

        if ($this->hasClass($root)) {
            $elements[] = $root;
        }

        return array_merge(
            $elements,
            $root->findElements(WebDriverBy::className($this->class)),
        );
    }

    private function hasClass(WebDriverElement $element): bool
    {
        return in_array($this->class, explode(' ', $element->getAttribute('class')), true);
    }
}
