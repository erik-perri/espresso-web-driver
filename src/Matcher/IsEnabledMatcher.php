<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class IsEnabledMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch($context, fn () => $this->matchElements($container->single(), $context));
    }

    /**
     * @return WebDriverElement[]
     */
    private function matchElements(WebDriverElement $container, MatchContext $context): array
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
