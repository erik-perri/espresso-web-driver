<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

use function EspressoWebDriver\withTagName;

final readonly class EspressoCore
{
    public function __construct(
        private WebDriver $driver,
        private EspressoOptions $options,
        private ?MatchResult $container = null,
    ) {
        //
    }

    public function goTo(string $url): self
    {
        $this->driver->get($url);

        return new self($this->driver, $this->options);
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function inContainer(MatcherInterface $matcher): self
    {
        $context = new EspressoContext(
            driver: $this->driver,
            options: $this->options,
        );

        $container = $this->container ?? $this->findHtmlElement();

        $result = $this->options->matchProcessor->process($container, $matcher, $context);

        return new self($this->driver, $this->options, $result);
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        $context = new EspressoContext(
            driver: $this->driver,
            options: $this->options,
        );

        $container = $this->container ?? $this->findHtmlElement();

        $result = $this->options->matchProcessor->process($container, $matcher, $context);

        return new ElementInteraction($result, $context);
    }

    /**
     * @throws NoMatchingElementException
     */
    private function findHtmlElement(): MatchResult
    {
        $matcher = withTagName('html');

        try {
            return new MatchResult(
                matcher: $matcher,
                result: [$this->driver->findElement(WebDriverBy::tagName('html'))],
            );
        } catch (NoSuchElementException) {
            throw new NoMatchingElementException($matcher);
        }
    }
}
