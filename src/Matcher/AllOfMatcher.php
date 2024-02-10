<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverElement;

final readonly class AllOfMatcher implements MatcherInterface
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
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElements($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElements(WebDriverElement $container): array
    {
        // Since we are waiting ourselves, we don't want the child matchers to wait as well.
        $instantOptions = new EspressoOptions(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
        );

        $elementsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $elementsByMatcher[] = $matcher->match($container, $instantOptions);
        }

        $result = array_shift($elementsByMatcher);

        if ($result === null) {
            return [];
        }

        foreach ($elementsByMatcher as $elements) {
            $result = array_uintersect($result, $elements, $this->compareElements(...));
        }

        return $result;
    }

    private function compareElements(WebDriverElement $a, WebDriverElement $b): int
    {
        return strcmp($a->getID(), $b->getID());
    }

    public function __toString(): string
    {
        return sprintf(
            'all(%1$s)',
            implode('; ', array_map(fn (MatcherInterface $matcher) => (string) $matcher, $this->matchers)),
        );
    }
}
