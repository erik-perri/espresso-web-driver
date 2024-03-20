<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

interface ProcessorResultInterface
{
    public function shouldRetry(): bool;
}
