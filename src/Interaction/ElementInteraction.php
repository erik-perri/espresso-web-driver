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
use EspressoWebDriver\Processor\ExpectedMatchCount;
use EspressoWebDriver\Processor\MatchProcessorOptions;
use Throwable;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(
        private MatcherInterface $target,
        private ?MatcherInterface $container,
        private EspressoContext $context,
    ) {
        //
    }

    /**
     * @throws AssertionFailedException
     */
    public function check(AssertionInterface $assertion): InteractionInterface
    {
        $message = null;
        $success = false;

        try {
            $success = $assertion->assert($this->target, $this->container, $this->context);
        } catch (AmbiguousElementException|NoMatchingElementException|NoRootElementException $exception) {
            $message = $exception->getMessage();

            if ($exception instanceof AmbiguousElementException) {
                $message = sprintf(
                    "%s\n%s",
                    $exception->getMessage(),
                    $this->context->options->elementLogger->describeMany($exception->elements),
                );
            }

            throw new AssertionFailedException($assertion, $exception);
        } catch (Throwable $exception) {
            $message = sprintf('Unexpected exception: %1$s', $exception->getMessage());

            throw new AssertionFailedException($assertion, $exception);
        } finally {
            $this->context->options->assertionReporter?->report(
                $success,
                $message
                    ? sprintf('Failed asserting that %1$s is true, %2$s', $assertion, $message)
                    : sprintf('Failed asserting that %1$s is true', $assertion),
            );
        }

        if (!$success) {
            throw new AssertionFailedException($assertion);
        }

        return $this;
    }

    /**
     * @throws PerformException
     */
    public function perform(ActionInterface ...$actions): InteractionInterface
    {
        foreach ($actions as $action) {
            try {
                $targetResult = $this->context->options->matchProcessor->process(
                    target: $this->target,
                    container: $this->container,
                    context: $this->context,
                    options: new MatchProcessorOptions(
                        expectedCount: ExpectedMatchCount::Single,
                    ),
                );

                if (!$action->perform($targetResult, $this->context)) {
                    throw new PerformException(
                        action: $action,
                        element: $this->context->options->elementLogger->describe($targetResult->single()),
                    );
                }
            } catch (AmbiguousElementException|NoMatchingElementException|NoRootElementException $exception) {
                throw new PerformException(
                    action: $action,
                    reason: $exception->getMessage(),
                );
            }
        }

        return $this;
    }
}
