<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\WebDriverElement;

final readonly class SubmitAction implements ActionInterface
{
    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        if (!in_array(strtolower($element->getTagName()), [
            'button',
            'form',
            'input',
            'select',
            'textarea',
        ])) {
            throw new PerformException($this, sprintf('element [%1$s] is not a form element', $element->getTagName()));
        }

        $element->submit();

        return true;
    }

    public function __toString(): string
    {
        return 'submit';
    }
}
