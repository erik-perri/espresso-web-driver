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

    public function match(WebDriverElement $root, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElements($root),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElements(WebDriverElement $root): array
    {
        $elementsByMatcher = [];

        // Since we are waiting ourselves, we don't want the child matchers to wait as well.
        $instantOptions = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
        );

        foreach ($this->matchers as $matcher) {
            $elementsByMatcher[] = $matcher->match($root, $instantOptions);
        }

        return array_merge(...$elementsByMatcher);
    }

    public function __toString(): string
    {
        return sprintf(
            'any(%1$s)',
            implode('; ', array_map(fn (MatcherInterface $matcher) => (string) $matcher, $this->matchers)),
        );
    }
}
