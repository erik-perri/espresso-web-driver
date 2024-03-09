<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

interface UrlProcessorInterface
{
    public function process(string $url): string;
}
