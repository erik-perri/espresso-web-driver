<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class SubmitAction implements ActionInterface
{
    /**
     * @throws PerformException
     */
    public function perform(WebDriverElement $target, ?MatcherInterface $container, EspressoContext $context): bool
    {
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
