<?php

declare(strict_types=1);

namespace EspressoWebDriver\Utilities;

use Facebook\WebDriver\WebDriverElement;

final readonly class ElementLogger implements ElementLoggerInterface
{
    /**
     * @var ElementLoggerInterface[]
     */
    private array $loggers;

    public function __construct(
        ElementLoggerInterface ...$loggers,
    ) {
        $this->loggers = array_values($loggers);
    }

    public function describe(WebDriverElement $element): string
    {
        return trim(implode(
            ' ',
            array_map(fn (ElementLoggerInterface $logger) => $logger->describe($element), $this->loggers),
        ));
    }
}
