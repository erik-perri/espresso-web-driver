<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithValueMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $text)
    {
        //
    }

    public function match(WebDriverElement $container, EspressoContext $context): array
    {
        return $this->wait(
            $context->options->waitTimeoutInSeconds,
            $context->options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithValue($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithValue(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getAttribute('value') === $this->text) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::xpath(sprintf('.//*[@value = "%1$s"]', $this->text))),
        );
    }

    public function __toString(): string
    {
        return sprintf('value="%1$s"', $this->text);
    }
}
