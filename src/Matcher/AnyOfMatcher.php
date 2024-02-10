<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use Facebook\WebDriver\WebDriverElement;

final readonly class AnyOfMatcher implements MatcherInterface
{
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
        $elementsByMatcher = [];

        foreach ($this->matchers as $matcher) {
            $elementsByMatcher[] = $matcher->match($root, $options);
        }

        return array_merge(...$elementsByMatcher);
    }
}
