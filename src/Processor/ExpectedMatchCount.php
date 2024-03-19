<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

enum ExpectedMatchCount: string
{
    case Any = 'any';
    case Many = 'many';
    case None = 'none';
    case Single = 'single';
}
