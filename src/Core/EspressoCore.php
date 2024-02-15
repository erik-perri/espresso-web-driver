<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Matcher\MatchContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

use function EspressoWebDriver\withTagName;

final readonly class EspressoCore
{
    private MatchResult $container;

    /**
     * @throws NoMatchingElementException
     */
    public function __construct(
        private WebDriver $driver,
        private EspressoOptions $options,
        ?MatchResult $container = null,
    ) {
        $this->container = $container ?? $this->findHtmlElement();
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function inContainer(MatcherInterface $matcher): self
    {
        $result = $matcher->match($this->container, new MatchContext(
            driver: $this->driver,
            isNegated: false,
            options: $this->options,
        ));

        return new self($this->driver, $this->options, $result);
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        $context = new EspressoContext($this->driver, $this->options);

        $result = $matcher->match($this->container, new MatchContext(
            driver: $this->driver,
            isNegated: false,
            options: $this->options,
        ));

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
