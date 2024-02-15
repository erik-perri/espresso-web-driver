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
            throw new PerformException($this, $element, 'not a form related element');
        }

        $element->submit();

        return true;
    }

    public function __toString(): string
    {
        return 'submit';
    }
}
