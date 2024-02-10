<?php

declare(strict_types=1);

namespace EspressoWebDriver\Traits;

use Closure;

trait HasAutomaticWait
{
    /**
     * @template T
     *
     * @param  Closure(): T  $callback
     */
    protected function wait(int $timeoutInSeconds, int $intervalInMilliseconds, Closure $callback): mixed
    {
        $start = time();
        $end = $start + $timeoutInSeconds;
        $lastResult = null;

        while (time() < $end) {
            $lastResult = $callback();

            if (!empty($lastResult)) {
                return $lastResult;
            }

            usleep($intervalInMilliseconds * 1000);
        }

        return $lastResult;
    }
}
