<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\MatchResult;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(
        private MatchResult $result,
        private EspressoContext $context,
    ) {
        //
    }

    /**
     * @throws AssertionFailedException
     */
    public function check(AssertionInterface $assertion): InteractionInterface
    {
        $success = false;

        try {
            $success = $assertion->assert($this->result, $this->context);

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
                    $this->result->describe($this->context->options->elementLogger),
                ),
            );
        }

        return $this;
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|PerformException
     */
    public function perform(ActionInterface ...$actions): InteractionInterface
    {
        $element = $this->result->single();

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
}
