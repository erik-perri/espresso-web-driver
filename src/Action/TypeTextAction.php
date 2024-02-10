<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use Facebook\WebDriver\WebDriverElement;

final readonly class TypeTextAction implements ActionInterface
{
    public function __construct(private string $text)
    {
        //
    }

    public function perform(WebDriverElement $element): bool
    {
        $element->sendKeys($this->text);

        return true;
    }

    public function __toString(): string
    {
        return sprintf('typeText("%1$s")', $this->text);
    }
}
