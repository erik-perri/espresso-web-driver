<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

final readonly class EspressoOptions
{
    public function __construct(
        public int $waitTimeoutInSeconds = 5,
        public int $waitIntervalInMilliseconds = 250,
        public ?AssertionReporterInterface $assertionReporter = null,
    ) {
        //
    }
}
