<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithIdMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $id)
    {
        //
    }

    public function match(WebDriverElement $root, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithId($root),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithId(WebDriverElement $root): array
    {
        $elements = [];

        if ($root->getAttribute('id') === $this->id) {
            $elements[] = $root;
        }

        return array_merge(
            $elements,
            $root->findElements(WebDriverBy::id($this->id)),
        );
    }

    public function __toString(): string
    {
        return sprintf('id="%1$s"', $this->id);
    }
}
