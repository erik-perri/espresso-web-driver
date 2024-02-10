<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Interaction\ElementInteraction;
use EspressoWebDriver\Interaction\InteractionInterface;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use RuntimeException;

final readonly class EspressoCore
{
    public function __construct(private WebDriver $driver, private EspressoOptions $options = new EspressoOptions)
    {
        //
    }

    public function onElement(MatcherInterface $assertion): InteractionInterface
    {
        $body = $this->driver->findElement(WebDriverBy::tagName('body'));

        $elements = $assertion->match($body, $this->options);

        if (empty($elements)) {
            throw new RuntimeException('Element not found');
        }

        if (count($elements) > 1) {
            throw new RuntimeException('Multiple elements found');
        }

        $element = reset($elements);

        return new ElementInteraction($element, $this->options);
    }
}
