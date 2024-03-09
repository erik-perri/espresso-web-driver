<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Utilities;

use Facebook\WebDriver\WebDriver;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final readonly class OutputManager
{
    public function __construct(private string $outputPath = __DIR__.'/../output')
    {
        //
    }

    public function clear(): void
    {
        $paths = [
            sprintf('%1$s/*.png', rtrim($this->outputPath, '/')),
            sprintf('%1$s/*-source.txt', rtrim($this->outputPath, '/')),
            sprintf('%1$s/*-console.txt', rtrim($this->outputPath, '/')),
        ];

        /** @var string[] $files */
        $files = array_merge(...array_map(fn (string $search) => glob($search) ?: [], $paths));

        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function save(WebDriver $driver, TestCase $testCase): void
    {
        $filePrefix = sprintf('%1$s/%2$s', $this->outputPath, $this->getTestName($testCase));

        $driver->takeScreenshot($filePrefix.'.png');

        file_put_contents($filePrefix.'-source.txt', $driver->getPageSource());

        $console = $driver->manage()->getLog('browser');
        if (count($console)) {
            file_put_contents($filePrefix.'-console.txt', json_encode($console, JSON_PRETTY_PRINT));
        }
    }

    private function getTestName(TestCase $testCase): string
    {
        $shortClassName = (new ReflectionClass($testCase))->getShortName();

        $testName = $testCase->name();

        if ($testCase->dataName()) {
            $testName .= '-'.$testCase->dataName();
        }

        return sprintf(
            '%1$s-%2$s',
            $shortClassName,
            $testName,
        );
    }
}
