<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

use EspressoWebDriver\Reporter\AssertionReporterInterface;

final readonly class EspressoOptions
{
    public function __construct(
        public int $waitTimeoutInSeconds = 5,
        public int $waitIntervalInMilliseconds = 250,
        public ?AssertionReporterInterface $assertionReporter = null,
    ) {
        //
    }

    public function toInstantOptions(): self
    {
        return new self(
            waitTimeoutInSeconds: 0,
            waitIntervalInMilliseconds: 0,
            assertionReporter: $this->assertionReporter,
        );
    }
}
