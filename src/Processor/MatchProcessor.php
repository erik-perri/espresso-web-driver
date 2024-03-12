<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;

use function EspressoWebDriver\withTagName;

class MatchProcessor implements MatchProcessorInterface
{
    public function process(
        MatcherInterface $target,
        MatcherInterface|MatchResult|null $container,
        EspressoContext $context,
        MatchProcessorOptions $options = new MatchProcessorOptions,
    ): MatchResult {
        $containerResult = $container instanceof MatchResult
            ? $container
            : $this->locateContainer($container, $context);

        return $this->locateTarget($target, $containerResult, $context);
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoRootElementException
     */
    private function locateContainer(?MatcherInterface $container, EspressoContext $context): MatchResult
    {
        $rootElement = $this->findHtmlElement($context);

        if ($container !== null) {
            return new MatchResult(
                container: $rootElement,
                matcher: $container,
                result: $container->match($rootElement, $context),
            );
        }

        return $rootElement;
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    private function locateTarget(
        MatcherInterface $target,
        MatchResult $container,
        EspressoContext $context,
    ): MatchResult {
        return new MatchResult(
            container: $container,
            matcher: $target,
            result: $target->match($container, $context),
        );
    }

    /**
     * @throws NoRootElementException
     */
    private function findHtmlElement(EspressoContext $context): MatchResult
    {
        $matcher = withTagName('html');

        try {
            return new MatchResult(
                container: null,
                matcher: $matcher,
                result: [$context->driver->findElement(WebDriverBy::tagName('html'))],
            );
        } catch (NoSuchElementException) {
            throw new NoRootElementException($matcher);
        }
    }
}
