<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

final readonly class TextNormalizer
{
    public function normalize(string $text): string
    {
        return trim((string) preg_replace("/\s+/", ' ', $text));
    }
}
