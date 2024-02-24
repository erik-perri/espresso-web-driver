<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Processor\MatchProcessor;
use EspressoWebDriver\Processor\MatchProcessorInterface;
use EspressoWebDriver\Reporter\AssertionReporterInterface;

final readonly class EspressoOptions
{
    public function __construct(
        public MatchProcessorInterface $matchProcessor = new MatchProcessor,
        public ?AssertionReporterInterface $assertionReporter = null,
    ) {
        //
    }
}
