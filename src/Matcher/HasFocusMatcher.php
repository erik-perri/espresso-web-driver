<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasFocusMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(WebDriverElement $root, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithFocus($root),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithFocus(WebDriverElement $root): array
    {
        $parent = $root->findElement(WebDriverBy::xpath('./parent::*'));

        return $parent->findElements(WebDriverBy::cssSelector(':focus'));
    }

    public function __toString(): string
    {
        return 'focused';
    }
}
