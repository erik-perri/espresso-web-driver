<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

enum ExpectedMatchCount: string
{
    case One = 'one';
    case OneOrMore = 'one_or_more';
    case TwoOrMore = 'two_or_more';
    case Zero = 'zero';
}
