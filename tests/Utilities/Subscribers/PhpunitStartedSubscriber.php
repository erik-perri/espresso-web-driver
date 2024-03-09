<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Utilities\Subscribers;

use EspressoWebDriver\Tests\Utilities\OutputManager;
use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;

final readonly class PhpunitStartedSubscriber implements StartedSubscriber
{
    public function __construct(private OutputManager $outputManager)
    {
        //
    }

    public function notify(Started $event): void
    {
        $this->outputManager->clear();
    }
}
