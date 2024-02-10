<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $text)
    {
        //
    }

    public function match(WebDriverElement $root, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithText($root),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithText(WebDriverElement $root): array
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
