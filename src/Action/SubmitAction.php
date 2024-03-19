<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Processor\MatchResult;

final readonly class SubmitAction implements ActionInterface
{
    public function perform(MatchResult $target, EspressoContext $context): bool
    {
        $target = $target->single();

        if (!in_array(strtolower($target->getTagName()), [
            'button',
            'form',
            'input',
            'select',
            'textarea',
        ])) {
            throw new PerformException(
                action: $this,
                element: $context->options->elementLogger->describe($target),
                reason: 'not a submittable element',
            );
        }

        $target->submit();

        return true;
    }

    public function __toString(): string
    {
        return 'submit';
    }
}
