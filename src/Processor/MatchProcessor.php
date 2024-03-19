<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
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
        ExpectedMatchCount $expectedCount,
    ): MatchResult {
        $containerResult = $container instanceof MatchResult
            ? $container
            : $this->locateContainer($container, $context);

        return new MatchResult(
            container: $containerResult,
            expectedCount: $expectedCount,
            matcher: $target,
            result: $target->match($containerResult, $context),
        );
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
                expectedCount: ExpectedMatchCount::One,
                matcher: $container,
                result: $container->match($rootElement, $context),
            );
        }

        return $rootElement;
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
                expectedCount: ExpectedMatchCount::One,
                matcher: $matcher,
                result: [$context->driver->findElement(WebDriverBy::tagName('html'))],
            );
        } catch (NoSuchElementException) {
            throw new NoRootElementException($matcher);
        }
    }
}
