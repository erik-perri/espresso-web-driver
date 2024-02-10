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

        while (time() < $end) {
            $result = $callback();

            if (!empty($result)) {
                return $result;
            }

            usleep($intervalInMilliseconds * 1000);
        }

        return null;
    }
}
