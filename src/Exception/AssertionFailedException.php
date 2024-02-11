<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Assertion\AssertionInterface;

class AssertionFailedException extends EspressoWebDriverException
{
    public function __construct(AssertionInterface $assertion)
    {
        parent::__construct(sprintf('Failed to assert %1$s', $assertion));
    }
}
