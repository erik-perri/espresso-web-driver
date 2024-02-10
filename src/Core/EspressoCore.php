<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Exceptions\AmbiguousElementMatcherException;
use EspressoWebDriver\Exceptions\NoMatchingElementException;
use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

use function EspressoWebDriver\withTagName;

final readonly class EspressoCore
{
    public function __construct(private WebDriver $driver, private EspressoOptions $options = new EspressoOptions)
    {
        //
    }

    /**
     * @throws NoMatchingElementException|AmbiguousElementMatcherException
     */
    public function onElement(MatcherInterface $matcher): InteractionInterface
    {
        try {
            $body = $this->driver->findElement(WebDriverBy::tagName('body'));
        } catch (NoSuchElementException) {
            throw new NoMatchingElementException(withTagName('body'));
        }

        $elements = $matcher->match($body, $this->options);
        $elementCount = count($elements);

        if ($elementCount === 0) {
            throw new NoMatchingElementException($matcher);
        }

        if ($elementCount > 1) {
            throw new AmbiguousElementMatcherException($elementCount, $matcher);
        }

        $element = reset($elements);

        return new ElementInteraction($element, $this->options);
    }
}
