<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

use function EspressoWebDriver\withTagName;

final readonly class EspressoCore
{
    private WebDriverElement $container;

    /**
     * @throws NoMatchingElementException
     */
    public function __construct(
        private WebDriver $driver,
        private EspressoOptions $options,
        ?WebDriverElement $container = null,
    ) {
        $this->container = $container ?? $this->findHtmlElement();
    }

    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function inContainer(MatcherInterface $matcher): self
    {
        $context = new EspressoContext($this->driver, $this->options);

        $result = new MatchResult($matcher, $matcher->match($this->container, $context));

        $container = $result->single();

        return new self($this->driver, $this->options, $container);
    }

    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        $context = new EspressoContext($this->driver, $this->options);

        $result = new MatchResult($matcher, $matcher->match($this->container, $context));

        return new ElementInteraction($result, $context);
    }

    /**
     * @throws NoMatchingElementException
     */
    private function findHtmlElement(): WebDriverElement
    {
        try {
            return $this->driver->findElement(WebDriverBy::tagName('html'));
        } catch (NoSuchElementException) {
            throw new NoMatchingElementException(withTagName('html'));
        }
    }
}
