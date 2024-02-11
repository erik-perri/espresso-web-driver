<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use EspressoWebDriver\Utilities\ElementDisplayChecker;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsDisplayedMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(WebDriverElement $container, EspressoContext $context): array
    {
        return $this->wait(
            $context->options->waitTimeoutInSeconds,
            $context->options->waitIntervalInMilliseconds,
            fn () => $this->findDisplayedElements($container, $context),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findDisplayedElements(WebDriverElement $container, EspressoContext $context): array
    {
        $elements = [];

        $checker = new ElementDisplayChecker($context->driver);

        if ($checker->isDisplayed($container)) {
            $elements[] = $container;
        }

        $potentiallyVisibleElements = $container->findElements(
            WebDriverBy::cssSelector('*:not([style*="display: none"]):not([style*="visibility: hidden"])'),
        );

        foreach ($potentiallyVisibleElements as $element) {
            if ($checker->isDisplayed($element)) {
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
