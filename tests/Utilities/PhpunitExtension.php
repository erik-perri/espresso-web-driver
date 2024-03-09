<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Utilities;

use EspressoWebDriver\Tests\Utilities\Subscribers\PhpunitStartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class PhpunitExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscribers(
            new PhpunitStartedSubscriber(new OutputManager),
        );
    }
}
