<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(WebDriverElement $root, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findSiblingElements($root),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findSiblingElements(WebDriverElement $root): array
    {
        $elements = [];

        $parent = $root->findElement(WebDriverBy::xpath('..'));

        $siblings = $parent->findElements(WebDriverBy::xpath('*'));

        // Since we are waiting ourselves, we don't want the child matchers to wait as well.
        $instantOptions = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
        );

        foreach ($siblings as $sibling) {
            if ($this->matcher->match($sibling, $instantOptions)) {
                $elements[] = $sibling;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('sibling(%1$s)', $this->matcher);
    }
}
