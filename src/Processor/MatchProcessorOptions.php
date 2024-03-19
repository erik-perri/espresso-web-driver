<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

final readonly class MatchProcessorOptions
{
    public function __construct(
        public ExpectedMatchCount $expectedCount = ExpectedMatchCount::Any,
    ) {
        //
    }
}
