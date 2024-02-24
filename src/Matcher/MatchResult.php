<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use Facebook\WebDriver\WebDriverElement;

final readonly class MatchResult
{
    /**
     * @var array<int, WebDriverElement>
     */
    private array $result;

    /**
     * @param  WebDriverElement[]  $result
     */
    public function __construct(
        private MatcherInterface $matcher,
        array $result,
        public bool $isExpectingEmpty = false,
    ) {
        $this->result = $this->removeDuplicates($result);
    }

    /**
     * @return WebDriverElement[]
     */
    public function all(): array
    {
        return $this->result;
    }

    public function count(): int
    {
        return count($this->result);
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException
     */
    public function single(): WebDriverElement
    {
        $elementCount = count($this->result);

        if ($elementCount === 0) {
            throw new NoMatchingElementException($this->matcher);
        }

        if ($elementCount > 1) {
            throw new AmbiguousElementException($this->result, $this->matcher);
        }

        return $this->result[0];
    }

    public function __toString(): string
    {
        return (string) $this->matcher;
    }

    /**
     * @param  WebDriverElement[]  $result
     * @return array<int, WebDriverElement>
     */
    private function removeDuplicates(array $result): array
    {
        $uniqueResults = [];

        foreach ($result as $element) {
            $uniqueResults[$element->getID()] = $element;
        }

        return array_values($uniqueResults);
    }
}
