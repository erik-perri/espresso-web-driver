<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\WebDriverElement;

final readonly class SubmitAction implements ActionInterface
{
    /**
     * @throws PerformException
     */
    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        if (!in_array(strtolower($element->getTagName()), [
            'button',
            'form',
            'input',
            'select',
            'textarea',
        ])) {
            throw new PerformException(
                action: $this,
                element: $context->options->elementLogger->describe($element),
                reason: 'not a submittable element',
            );
        }

        $element->submit();

        return true;
    }

    public function __toString(): string
    {
        return 'submit';
    }
}
