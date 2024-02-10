<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithClassMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $class)
    {
        //
    }

    public function match(WebDriverElement $container, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithClass($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithClass(WebDriverElement $container): array
    {
        $elements = [];

        if ($this->hasClass($container)) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::className($this->class)),
        );
    }

    private function hasClass(WebDriverElement $element): bool
    {
        $classes = $element->getAttribute('class');

        if (!$classes) {
            return false;
        }

        $classNames = array_map('trim', explode(' ', $classes));

        return in_array($this->class, $classNames, true);
    }

    public function __toString(): string
    {
        return sprintf('class="%1$s"', $this->class);
    }
}
