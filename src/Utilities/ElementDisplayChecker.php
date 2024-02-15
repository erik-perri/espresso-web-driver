<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use EspressoWebDriver\Exception\EspressoWebDriverException;
use Facebook\WebDriver\JavaScriptExecutor;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;

final class ElementDisplayChecker
{
    private int $scrollX = 0;

    private int $scrollY = 0;

    private int $viewportWidth = 0;

    private int $viewportHeight = 0;

    /**
     * @throws EspressoWebDriverException
     */
    public function __construct(private readonly WebDriver $driver)
    {
        $this->updateViewport();
    }

    public function isDisplayed(WebDriverElement $element): bool
    {
        if (!$element->isDisplayed()) {
            return false;
        }

        $elementClientLocation = $element->getLocation();
        $elementClientRect = $element->getSize();

        $elementBottom = $elementClientLocation->getY() + $elementClientRect->getHeight();
        $elementRight = $elementClientLocation->getX() + $elementClientRect->getWidth();

        return $elementClientLocation->getY() >= $this->scrollY &&
            $elementClientLocation->getX() >= $this->scrollX &&
            $elementBottom <= ($this->scrollY + $this->viewportHeight) &&
            $elementRight <= ($this->scrollX + $this->viewportWidth);
    }

    public function updateViewport(): void
    {
        if (!($this->driver instanceof JavaScriptExecutor)) {
            // TODO Custom exception?
            throw new EspressoWebDriverException(
                'Cannot check displayed state, driver does not have access to executeScript',
            );
        }

        $this->scrollX = $this->driver->executeScript('return window.scrollX;');
        $this->scrollY = $this->driver->executeScript('return window.scrollY;');
        $this->viewportWidth = $this->driver->executeScript(
            'return window.innerWidth || document.documentElement.clientWidth;',
        );
        $this->viewportHeight = $this->driver->executeScript(
            'return window.innerHeight || document.documentElement.clientHeight;',
        );
    }
}
