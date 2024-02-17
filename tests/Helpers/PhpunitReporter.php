<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Helpers;

use EspressoWebDriver\Reporter\AssertionReporterInterface;
use PHPUnit\Framework\Assert;

final readonly class PhpunitReporter implements AssertionReporterInterface
{
    public function report(bool $valid, string $message): void
    {
        Assert::assertTrue($valid, $message);
    }
}
