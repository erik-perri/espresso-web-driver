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
    ) {
        //
    }

    public function count(): int
    {
        return count($this->result);
    }

    /**
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    public function single(): WebDriverElement
    {
        $elementCount = count($this->result);

        if ($elementCount === 0) {
            throw new NoMatchingElementException($this->matcher);
        }

        if ($elementCount > 1) {
            throw new AmbiguousElementMatcherException($elementCount, $this->matcher);
        }

        return array_values($this->result)[0];
    }
}
