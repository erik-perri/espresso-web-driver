<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoOptions;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private string $text)
    {
        //
    }

    public function match(WebDriverElement $container, EspressoOptions $options): array
    {
        return $this->wait(
            $options->waitTimeoutInSeconds,
            $options->waitIntervalInMilliseconds,
            fn () => $this->findElementsWithText($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithText(WebDriverElement $container): array
    {
        $elements = [];

        if ($container->getText() === $this->text) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(WebDriverBy::xpath(sprintf('//*[text()="%1$s"]', $this->text))),
        );
    }

    public function __toString(): string
    {
        return sprintf('text="%1$s"', $this->text);
    }
}
