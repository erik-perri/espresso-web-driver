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
        try {
            $result = $assertion->assert($this->result, $this->context);

            $this->context->options->assertionReporter?->report(
                $result,
                sprintf('Failed asserting that %1$s is true for %2$s', $assertion, $this->result),
            );

            if (!$result) {
                throw new AssertionFailedException($assertion);
            }
        } catch (AmbiguousElementException|NoMatchingElementException $exception) {
            $this->context->options->assertionReporter?->report(
                false,
                sprintf(
                    'Failed asserting that %1$s is true for %2$s. %3$s',
                    $assertion,
                    $this->result,
                    $exception->getMessage(),
                ),
            );

            throw new AssertionFailedException($assertion, $exception);
        }

        return $this;
    }

    /**
     * @throws AmbiguousElementException|PerformException|NoMatchingElementException
     */
    public function perform(ActionInterface ...$actions): InteractionInterface
    {
        $element = $this->result->single();

        foreach ($actions as $action) {
            if (!$action->perform($element, $this->context)) {
                throw new PerformException($action);
            }
        }

        return $this;
    }
}
