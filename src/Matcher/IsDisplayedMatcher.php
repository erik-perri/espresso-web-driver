<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(WebDriverElement $container, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findDisplayedElements($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findDisplayedElements(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->isDisplayed()) {
            $elements[] = $container;
        }

        $visibleElements = $container->findElements(
            WebDriverBy::cssSelector('*:not([style*="display: none"]):not([style*="visibility: hidden"])'),
        );

        foreach ($visibleElements as $element) {
            if ($element->isDisplayed()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return 'displayed';
    }
}
