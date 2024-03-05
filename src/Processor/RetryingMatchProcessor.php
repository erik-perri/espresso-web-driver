<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Matcher\MatchResult;

class RetryingMatchProcessor implements MatchProcessorInterface
{
    public function __construct(
        public int $waitTimeoutInSeconds = 5,
        public int $waitIntervalInMilliseconds = 250,
    ) {
        //
    }

    public function process(MatchResult $previous, MatcherInterface $matcher, EspressoContext $context): MatchResult
    {
        $startTime = (float) microtime(true);
        $endTime = $startTime + $this->waitTimeoutInSeconds;
        $waitIntervalInMicroseconds = $this->waitIntervalInMilliseconds * 1000;
        $lastResult = new MatchResult(matcher: $matcher, result: []);

        while (microtime(true) < $endTime) {
            $lastResult = $matcher->match($previous, $context);

            if ($lastResult->count()) {
                break;
            }

            usleep($waitIntervalInMicroseconds);
        }

        return $lastResult;
    }
}
