<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class WithTextContainingMatcher implements MatcherInterface
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
            fn () => $this->findElementsWithText($container),
        );
    }

    /**
     * @return WebDriverElement[]
     */
    private function findElementsWithText(WebDriverElement $container): array
    {
        $elements = [];

        if (mb_stripos($container->getText(), $this->text) !== false) {
            $elements[] = $container;
        }

        return array_merge(
            $elements,
            $container->findElements(
                // TODO Figure out a better path that works for all languages
                //      XPath's newer lower-case() or matches() are not supported
                WebDriverBy::xpath(sprintf(
                    './/*[contains(%1$s, "%2$s")]',
                    'translate(text(), "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")',
                    $this->text,
                )),
            ),
        );
    }

    public function __toString(): string
    {
        return sprintf('textContaining="%1$s"', $this->text);
    }
}
