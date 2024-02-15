<?php

declare(strict_types=1);

namespace EspressoWebDriver\Exception;

use Exception;

class EspressoWebDriverException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
