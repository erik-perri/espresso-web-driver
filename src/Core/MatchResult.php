<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Utilities\ElementLoggerInterface;
use Facebook\WebDriver\WebDriverBy;
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
        public ?MatchResult $container,
        public MatcherInterface $matcher,
        array $result,
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

    public function describe(ElementLoggerInterface $elementLogger): string
    {
        $totalElements = count($this->result);

        if ($totalElements === 0) {
            return sprintf('no elements found for %s', $this->matcher);
        }

        return sprintf(
            '%1$s %2$s found for %3$s%4$s',
            number_format($totalElements),
            $totalElements === 1 ? 'element' : 'elements',
            $this->matcher,
            "\n".$elementLogger->describeMany($this->result),
        );
    }

    /**
     * @return array<int, WebDriverElement>
     */
    public function findElements(WebDriverBy $locator): array
    {
        $elementsByResult = [];

        foreach ($this->result as $element) {
            $elementsByResult[] = $element->findElements($locator);
        }

        return $this->removeDuplicates(array_merge(...$elementsByResult));
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
