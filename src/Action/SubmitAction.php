<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use Facebook\WebDriver\WebDriverElement;
use RuntimeException;

final readonly class SubmitAction implements ActionInterface
{
    public function __construct()
    {
        //
    }

    public function perform(WebDriverElement $element): bool
    {
        if (!in_array(strtolower($element->getTagName()), [
            'button',
            'form',
            'input',
            'select',
            'textarea',
        ])) {
            throw new RuntimeException(sprintf('Element [%1$s] is not a form element', $element->getTagName()));
        }

        $element->submit();

        return true;
    }

    public function __toString(): string
    {
        return 'submit';
    }
}
