<?php

declare(strict_types=1);

namespace EspressoWebDriver\Reporter;

use PHPUnit\Framework\Assert;

final readonly class PhpunitReporter implements AssertionReporterInterface
{
    public function report(bool $valid, string $message): void
    {
        Assert::assertTrue($valid, $message);
    }
}
