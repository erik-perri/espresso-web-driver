<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriver;

final readonly class EspressoCore
{
    public function __construct(
        private WebDriver $driver,
        private EspressoOptions $options,
        private ?MatcherInterface $containerMatcher = null,
    ) {
        //
    }

    public function goTo(string $url): self
    {
        $this->driver->get($url);

        return $this;
    }

    public function inContainer(MatcherInterface $matcher): self
    {
        return new self($this->driver, $this->options, $matcher);
    }

    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        $context = new EspressoContext(
            driver: $this->driver,
            options: $this->options,
        );

        return new ElementInteraction($matcher, $context, $this->containerMatcher);
    }
}
