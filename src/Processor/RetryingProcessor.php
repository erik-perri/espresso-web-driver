<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use Closure;
use RuntimeException;
use Throwable;

readonly class RetryingProcessor
{
    /**
     * @param  class-string[]  $retryableExceptions
     */
    public function __construct(
        private int $waitTimeoutInMilliseconds = 5000,
        private int $waitIntervalInMilliseconds = 250,
        private array $retryableExceptions = [],
    ) {
        //
    }

    /**
     * @param  Closure(): ProcessorResultInterface  $processor
     *
     * @throws Throwable
     */
    public function process(Closure $processor): ProcessorResultInterface
    {
        $startTime = (float) microtime(true);
        $endTime = $startTime + $this->waitTimeoutInMilliseconds / 1000;
        $waitIntervalInMicroseconds = $this->waitIntervalInMilliseconds * 1000;
        $lastResult = null;
        $lastException = null;

        while (microtime(true) < $endTime) {
            try {
                $lastException = null;
                $lastResult = $processor();
            } catch (Throwable $exception) {
                if (!in_array(get_class($exception), $this->retryableExceptions, true)) {
                    throw $exception;
                }

                $lastException = $exception;

                usleep($waitIntervalInMicroseconds);

                continue;
            }

            if (!$lastResult->shouldRetry()) {
                break;
            }

            usleep($waitIntervalInMicroseconds);
        }

        if ($lastException !== null) {
            throw $lastException;
        }

        if ($lastResult === null) {
            throw new RuntimeException('No result processed. Ensure the wait timeout is greater than 0.');
        }

        return $lastResult;
    }
}
