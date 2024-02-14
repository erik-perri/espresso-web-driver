<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\PerformException;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverHasInputDevices;
use Facebook\WebDriver\WebDriverKeys;
use ReflectionClass;
use ReflectionClassConstant;

/**
 * Sends the given keys to the browser.
 */
final readonly class SendKeysAction implements ActionInterface
{
    /**
     * @var array<int, string>
     */
    private array $keys;

    /**
     * @see WebDriverKeys
     */
    public function __construct(string ...$keys)
    {
        $this->keys = array_values($keys);
    }

    /**
     * @throws PerformException
     */
    public function perform(WebDriverElement $element, EspressoContext $context): bool
    {
        if (!($context->driver instanceof WebDriverHasInputDevices)) {
            throw new PerformException($this, 'driver does not have access to input devices');
        }

        $context->driver->getKeyboard()->sendKeys(implode('', $this->keys));

        return true;
    }

    public function __toString(): string
    {
        $keys = [];
        $webDriverKeys = new ReflectionClass(WebDriverKeys::class);
        $knownKeys = $webDriverKeys->getConstants(ReflectionClassConstant::IS_PUBLIC);

        foreach ($this->keys as $key) {
            $constant = array_search($key, $knownKeys);

            $keys[] = $constant !== false ? $constant : $key;
        }

        return sprintf('sendKeys(%1$s)', implode(', ', $keys));
    }
}
