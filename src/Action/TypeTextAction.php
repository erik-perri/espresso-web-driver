<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\WebDriverElement;

/**
 * Selects the matched element and types the given text into it.
 */
final readonly class TypeTextAction implements ActionInterface
{
    public function __construct(private string $text)
    {
        //
    }

    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        $element->sendKeys($this->text);

        return true;
    }

    public function __toString(): string
    {
        return sprintf('typeText(%1$s)', $this->text);
    }
}
