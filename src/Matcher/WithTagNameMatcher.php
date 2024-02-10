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

    public function match(WebDriverElement $container, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithTagName($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithTagName(WebDriverElement $container): array
    {
        $elements = [];

        if (strcasecmp($container->getTagName(), $this->tagName) === 0) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::tagName($this->tagName)),
        );
    }

    public function __toString(): string
    {
        return sprintf('tagName="%1$s"', $this->tagName);
    }
}
