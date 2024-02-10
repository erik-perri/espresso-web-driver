<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTagNameMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $tagName)
    {
        //
    }

    public function match(WebDriverElement $root, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithTagName($root),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithTagName(WebDriverElement $root): array
    {
        $elements = [];

        if (strcasecmp($root->getTagName(), $this->tagName) === 0) {
            $elements[] = $root;
        }

        return array_merge(
            $elements,
            $root->findElements(WebDriverBy::tagName($this->tagName)),
        );
    }

    public function __toString(): string
    {
        return sprintf('tagName="%s"', $this->tagName);
    }
}
