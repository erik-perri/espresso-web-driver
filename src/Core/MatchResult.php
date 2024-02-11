<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\WebDriverElement;

final readonly class MatchResult
{
    /**
     * @param  WebDriverElement[]  $result
     */
    public function __construct(
        private MatcherInterface $matcher,
        private array $result,
        private EspressoContext $context,
    ) {
        //
    }

    public function count(): int
    {
        return count($this->result);
    }

    public function single(): WebDriverElement
    {
        $elementCount = count($this->result);

        if ($elementCount === 0) {
            $exception = new NoMatchingElementException($this->matcher);

            $this->context->options->assertionReporter?->report(false, $exception->getMessage());

            throw $exception;
        }

        if ($elementCount > 1) {
            $exception = new AmbiguousElementMatcherException($elementCount, $this->matcher);

            $this->context->options->assertionReporter?->report(false, $exception->getMessage());

            throw $exception;
        }

        return array_values($this->result)[0];
    }
}
