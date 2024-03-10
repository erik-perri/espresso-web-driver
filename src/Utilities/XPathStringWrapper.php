<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

/**
 * Wraps text in quotes for XPath queries, splitting into parts using concat() when it contains both single and double
 * quotes.
 */
class XPathStringWrapper
{
    public function wrap(string $text): string
    {
        if (!str_contains($text, "'")) {
            return "'$text'";
        }

        if (!str_contains($text, '"')) {
            return "\"$text\"";
        }

        $remainingText = $text;
        $concatParts = [];

        while ($remainingText) {
            [$nextPart, $nextQuote] = $this->findQuotableSegment($remainingText);

            $concatParts[] = $nextQuote.$nextPart.$nextQuote;

            $remainingText = substr($remainingText, strlen($nextPart));
        }

        return 'concat('.implode(', ', $concatParts).')';
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function findQuotableSegment(string $remainingText): array
    {
        $nextSingleQuote = strpos($remainingText, "'");
        if ($nextSingleQuote === false) {
            return [$remainingText, "'"];
        }

        $nextDoubleQuote = strpos($remainingText, '"');
        if ($nextDoubleQuote === false) {
            return [$remainingText, '"'];
        }

        if ($nextSingleQuote > $nextDoubleQuote) {
            return [substr($remainingText, 0, $nextSingleQuote), "'"];
        }

        return [substr($remainingText, 0, $nextDoubleQuote), '"'];
    }
}
