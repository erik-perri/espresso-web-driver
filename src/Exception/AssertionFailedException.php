<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use EspressoWebDriver\Assertion\AssertionInterface;
use Throwable;

class AssertionFailedException extends EspressoWebDriverException
{
    public function __construct(AssertionInterface $assertion, ?Throwable $previous = null)
    {
        parent::__construct(
            $previous
                ? sprintf('Failed to assert %1$s, %2$s', $assertion, $previous->getMessage())
                : sprintf('Failed to assert %1$s', $assertion),
        );
    }
}
