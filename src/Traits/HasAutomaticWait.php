<?php

declare(strict_types=1);

namespace EspressoWebDriver\Traits;

use Closure;

trait HasAutomaticWait
{
    /**
     * Executes the provided callback function in intervals until it returns a non-empty result or the timeout is reached.
     *
     * @template T
     *
     * @param  Closure(): T  $callback
     */
    protected function wait(int $timeoutInSeconds, int $intervalInMilliseconds, Closure $callback): mixed
    {
        if ($timeoutInSeconds < 1) {
            return $callback();
        }

        $start = (float) microtime(true);
        $end = $start + $timeoutInSeconds;
        $lastResult = null;

        while (microtime(true) < $end) {
            $lastResult = $callback();

            if (!empty($lastResult)) {
                return $lastResult;
            }

            usleep($intervalInMilliseconds * 1000);
        }

        return $lastResult;
    }
}
