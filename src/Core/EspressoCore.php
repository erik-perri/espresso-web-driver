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
        private ?MatcherInterface $container = null,
    ) {
        //
    }

    public function inContainer(MatcherInterface $matcher): self
    {
        return new self($this->driver, $this->options, $matcher);
    }

    public function navigateTo(string $url): self
    {
        $this->driver->get(
            $this->options->urlProcessor
                ? $this->options->urlProcessor->process($url)
                : $url,
        );

        return $this;
    }

    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        $context = new EspressoContext(
            driver: $this->driver,
            options: $this->options,
        );

        return new ElementInteraction($matcher, $this->container, $context);
    }
}
