<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use Facebook\WebDriver\WebDriverElement;

final readonly class ElementAttributeLogger implements ElementLoggerInterface
{
    public function describe(WebDriverElement $element): string
    {
        $notableAttributeNames = [
            'alt',
            'class',
            'disabled',
            'href',
            'id',
            'name',
            'src',
            'title',
            'type',
            'value',
        ];

        $notableAttributes = array_reduce(
            $notableAttributeNames,
            fn (array $carry, string $name) => $element->getAttribute($name)
                ? [$name => $element->getAttribute($name)]
                : $carry,
            [],
        );

        $tagInner = trim(sprintf(
            '%1$s %2$s',
            $element->getTagName(),
            implode(' ', array_map(
                fn (string $name, string $value) => sprintf('%1$s="%2$s"', $name, $value),
                array_keys($notableAttributes),
                $notableAttributes,
            )),
        ));

        return sprintf('<%1$s>', $tagInner);
    }
}
