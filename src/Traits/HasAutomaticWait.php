<?php

declare(strict_types=1);

namespace EspressoWebDriver\Traits;

use Closure;
use EspressoWebDriver\Matcher\MatchContext;
use EspressoWebDriver\Matcher\MatchResult;
use Facebook\WebDriver\WebDriverElement;

trait HasAutomaticWait
{
    /**
     * Executes the provided callback function in intervals until it returns a non-empty result or the timeout is reached.
     *
     * @param  Closure(): array<WebDriverElement>  $callback
     */
    protected function waitForMatch(MatchContext $context, Closure $callback): MatchResult
    {
        if ($context->options->waitTimeoutInSeconds < 1) {
            return new MatchResult(
                matcher: $this,
                result: $callback(),
                isNegated: $context->isNegated,
            );
        }

        $startTime = (float) microtime(true);
        $endTime = $startTime + $context->options->waitTimeoutInSeconds;
        $waitIntervalInMicroseconds = $context->options->waitIntervalInMilliseconds * 1000;
        $lastResult = [];

        while (microtime(true) < $endTime) {
            $lastResult = $callback();

            if (!empty($lastResult)) {
                break;
            }

            usleep($waitIntervalInMicroseconds);
        }

        return new MatchResult(
            matcher: $this,
            result: $lastResult,
            isNegated: $context->isNegated,
        );
    }
}
