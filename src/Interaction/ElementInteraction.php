<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;

use function EspressoWebDriver\withTagName;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(
        private MatcherInterface $matcher,
        private EspressoContext $context,
        private ?MatcherInterface $containerMatcher,
    ) {
        //
    }

    /**
     * @throws AmbiguousElementException|AssertionFailedException|NoMatchingElementException
     */
    public function check(AssertionInterface $assertion): InteractionInterface
    {
        $success = false;

        try {
            $result = $this->result();
        } catch (NoRootElementException $e) {
            $this->context->options->assertionReporter?->report(
                false,
                sprintf(
                    'Failed asserting that %1$s is true, %2$s',
                    $assertion,
                    $e->getMessage(),
                ),
            );

            throw new AssertionFailedException($assertion, $e);
        }

        try {
            $success = $assertion->assert($result, $this->context);

            if (!$success) {
                throw new AssertionFailedException($assertion);
            }
        } catch (AmbiguousElementException|NoMatchingElementException $exception) {
            throw new AssertionFailedException($assertion, $exception);
        } finally {
            $this->context->options->assertionReporter?->report(
                $success,
                sprintf(
                    'Failed asserting that %1$s is true, %2$s',
                    $assertion,
                    $result->describe($this->context->options->elementLogger),
                ),
            );
        }

        return $this;
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoRootElementException|PerformException
     */
    public function perform(ActionInterface ...$actions): InteractionInterface
    {
        $element = $this->result()->single();

        foreach ($actions as $action) {
            if (!$action->perform($element, $this->context)) {
                throw new PerformException(
                    action: $action,
                    element: $this->context->options->elementLogger->describe($element),
                );
            }
        }

        return $this;
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoRootElementException
     */
    private function result(): MatchResult
    {
        $rootElement = $this->findHtmlElement();

        if ($this->containerMatcher !== null) {
            $rootElement = $this->context->options->matchProcessor->process(
                $rootElement,
                $this->containerMatcher,
                $this->context,
            );
        }

        return $this->context->options->matchProcessor->process($rootElement, $this->matcher, $this->context);
    }

    /**
     * @throws NoRootElementException
     */
    private function findHtmlElement(): MatchResult
    {
        $matcher = withTagName('html');

        try {
            return new MatchResult(
                matcher: $matcher,
                result: [$this->context->driver->findElement(WebDriverBy::tagName('html'))],
            );
        } catch (NoSuchElementException) {
            throw new NoRootElementException($matcher);
        }
    }
}
