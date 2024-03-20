<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

final readonly class ActionResult implements ProcessorResultInterface
{
    public function __construct(private bool $result)
    {
        //
    }

    public function result(): bool
    {
        return $this->result;
    }

    public function shouldRetry(): bool
    {
        return false;
    }
}
