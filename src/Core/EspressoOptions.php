<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Processor\MatchProcessor;
use EspressoWebDriver\Processor\MatchProcessorInterface;
use EspressoWebDriver\Processor\UrlProcessorInterface;
use EspressoWebDriver\Reporter\AssertionReporterInterface;
use EspressoWebDriver\Utilities\ElementLocator;
use EspressoWebDriver\Utilities\ElementLocatorInterface;
use EspressoWebDriver\Utilities\ElementLoggerInterface;
use EspressoWebDriver\Utilities\ElementPathLogger;

final readonly class EspressoOptions
{
    public function __construct(
        public ?AssertionReporterInterface $assertionReporter = null,
        public ElementLocatorInterface $elementLocator = new ElementLocator,
        public ElementLoggerInterface $elementLogger = new ElementPathLogger,
        public MatchProcessorInterface $matchProcessor = new MatchProcessor,
        public ?UrlProcessorInterface $urlProcessor = null,
    ) {
        //
    }
}
