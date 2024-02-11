<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasFocusMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(WebDriverElement $container, EspressoContext $context): array
    {
        return $this->wait(
            $context->options->waitTimeoutInSeconds,
            $context->options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithFocus($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithFocus(WebDriverElement $container): array
    {
        try {
            $parent = $container->findElement(WebDriverBy::xpath('./parent::*'));
        } catch (NoSuchElementException) {
            return [];
        }

        return $parent->findElements(WebDriverBy::cssSelector(':focus'));
    }

    public function __toString(): string
    {
        return 'focused';
    }
}
