<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverElement;

final readonly class AnyOfMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    /**
     * @var MatcherInterface[]
     */
    private array $matchers;

    public function __construct(MatcherInterface ...$matchers)
    {
        $this->matchers = $matchers;
    }

    public function match(WebDriverElement $container, EspressoContext $context): array
    {
        // Since we are waiting ourselves, we don't want the child matchers to wait as well.
        $instantOptions = $context->options->toInstantOptions();

        return $this->wait(
            $context->options->waitTimeoutInSeconds,
            $context->options->waitIntervalInMilliseconds,
            fn () => $this->findElements($container, new EspressoContext($context->driver, $instantOptions)),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElements(WebDriverElement $container, EspressoContext $context): array
    {
        $elementsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $elementsByMatcher[] = $matcher->match($container, $context);
        }

        $mergedElements = array_merge(...$elementsByMatcher);
        $elementsById = [];

        foreach ($mergedElements as $element) {
            if (isset($elementsById[$element->getID()])) {
                continue;
            }

            $elementsById[$element->getID()] = $element;
        }

        return array_values($elementsById);
    }

    public function __toString(): string
    {
        return sprintf(
            'any(%1$s)',
            implode('; ', array_map(fn (MatcherInterface $matcher) => (string) $matcher, $this->matchers)),
        );
    }
}
