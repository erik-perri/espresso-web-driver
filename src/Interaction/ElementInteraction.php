<?php

declare(strict_types=1);

namespace EspressoWebDriver\Interaction;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Assertion\AssertionInterface;
use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\AssertionFailedException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\PerformException;

final readonly class ElementInteraction implements InteractionInterface
{
    public function __construct(
        private MatchResult $result,
        private EspressoContext $context,
    ) {
        //
    }

    /**
     * @throws AmbiguousElementMatcherException|AssertionFailedException|NoMatchingElementException
     */
    public function check(AssertionInterface $assertion): InteractionInterface
    {
        try {
            $result = $assertion->assert($this->result, $this->context);

            $this->context->options->assertionReporter?->report(
                $result,
                sprintf('Failed asserting that %s is true', $assertion),
            );

            if (!$result) {
                throw new AssertionFailedException($assertion);
            }
        } catch (AmbiguousElementMatcherException|NoMatchingElementException $exception) {
            $this->context->options->assertionReporter?->report(false, $exception->getMessage());
        }

        return $this;
    }

    /**
     * @throws AmbiguousElementMatcherException|PerformException|NoMatchingElementException
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
