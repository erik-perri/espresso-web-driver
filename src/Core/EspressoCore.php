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
        private EspressoOptions $options = new EspressoOptions,
        ?WebDriverElement $container = null,
    ) {
        $this->container = $container ?? $this->findHtmlElement();
    }

    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function inContainer(MatcherInterface $matcher): self
    {
        $container = $this->findSingleMatch($matcher);

        return new self($this->driver, $this->options, $container);
    }

    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        $element = $this->findSingleMatch($matcher);

        return new ElementInteraction($element, $this->options);
    }

    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    private function findSingleMatch(MatcherInterface $matcher): WebDriverElement
    {
        $elements = $matcher->match($this->container, $this->options);
        $elementCount = count($elements);

        if ($elementCount === 0) {
            $exception = new NoMatchingElementException($matcher);

            $this->options->assertionReporter?->report(false, $exception->getMessage());

            throw $exception;
        }

        if ($elementCount > 1) {
            $exception = new AmbiguousElementMatcherException($elementCount, $matcher);

            $this->options->assertionReporter?->report(false, $exception->getMessage());

            throw $exception;
        }

        return reset($elements);
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
