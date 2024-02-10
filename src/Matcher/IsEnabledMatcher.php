<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsEnabledMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(WebDriverElement $container, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findEnabledElements($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findEnabledElements(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->isEnabled()) {
            $elements[] = $container;
        }

        $possibleElements = $container->findElements(
            WebDriverBy::cssSelector('button, fieldset, optgroup, option, select, textarea, input'),
        );

        foreach ($possibleElements as $element) {
            if ($element->isEnabled()) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return 'enabled';
    }
}
