<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
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
            fn () => $this->findElements($container, $instantOptions),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElements(WebDriverElement $container, EspressoOptions $options): array
    {
        $elementsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $elementsByMatcher[] = $matcher->match($container, $options);
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
