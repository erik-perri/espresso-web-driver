<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithIdMatcher implements MatcherInterface
{
    public function __construct(private string $id)
    {
        //
    }

    public function match(WebDriverElement $root): array
    {
        $elements = [];

        if ($root->getId() === $this->id) {
            $elements[] = $root;
        }

        return array_merge(
            $elements,
            $root->findElements(WebDriverBy::id($this->id)),
        );
    }
}
