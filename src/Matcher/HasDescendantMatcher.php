<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasDescendantMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(WebDriverElement $container, EspressoContext $context): array
    {
        // Since we are waiting ourselves, we don't want the child matchers to wait as well.
        $instantOptions = $context->options->toInstantOptions();

        return $this->wait(
            $context->options->waitTimeoutInSeconds,
            $context->options->waitIntervalInMilliseconds,
            fn () => $this->findDescendantElements($container, new EspressoContext($context->driver, $instantOptions)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findDescendantElements(WebDriverElement $container, EspressoContext $context): array
    {
        $descendants = $this->matcher->match($container, $context);

        $elements = [];

        foreach ($descendants as $descendant) {
            $ancestors = array_reverse($descendant->findElements(WebDriverBy::xpath('./ancestor::*')));

            foreach ($ancestors as $ancestor) {
                if ($ancestor->getID() === $container->getID()) {
                    break;
                }

                $elements[] = $ancestor;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('descendant(%1$s)', $this->matcher);
    }
}
