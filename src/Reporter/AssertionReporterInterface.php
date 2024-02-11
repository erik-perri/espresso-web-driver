<?php

declare(strict_types=1);

namespace EspressoWebDriver\Reporter;

interface AssertionReporterInterface
{
    public function report(bool $valid, string $message): void;
}
