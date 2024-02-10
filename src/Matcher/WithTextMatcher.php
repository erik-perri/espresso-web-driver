<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextMatcher implements MatcherInterface
{
    public function __construct(private string $text)
    {
        //
    }

    public function match(WebDriverElement $root): array
    {
        $elements = [];

        if ($root->getText() === $this->text) {
            $elements[] = $root;
        }

        return array_merge(
            $elements,
            $root->findElements(WebDriverBy::xpath(sprintf('//*[text()="%s"]', $this->text))),
        );
    }
}
