<?php

declare(strict_types=1);

namespace EspressoWebDriver\Core;

interface AssertionReporterInterface
{
    public function report(bool $valid, string $message): void;
}
