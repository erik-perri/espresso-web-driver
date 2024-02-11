<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasSiblingMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(WebDriverElement $container, EspressoOptions $options): array
    {
        // Since we are waiting ourselves, we don't want the child matchers to wait as well.
        $instantOptions = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
            assertionReporter: $options->assertionReporter,
        );

        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findSiblingElements($container, $instantOptions),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findSiblingElements(WebDriverElement $container, EspressoOptions $options): array
    {
        $elements = [];

        $potentialSiblings = $this->matcher->match($container, $options);

        foreach ($potentialSiblings as $sibling) {
            try {
                $parent = $sibling->findElement(WebDriverBy::xpath('./parent::*'));

                $adjacentChildren = $parent->findElements(WebDriverBy::xpath('*'));

                foreach ($adjacentChildren as $child) {
                    if ($child->getID() !== $sibling->getID()) {
                        $elements[] = $child;
                    }
                }
            } catch (NoSuchElementException) {
                // If we couldn't find the parent, we can't find the siblings.
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('sibling(%1$s)', $this->matcher);
    }
}
