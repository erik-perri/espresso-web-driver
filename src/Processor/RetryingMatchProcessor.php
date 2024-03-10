<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Core\MatchResult;
use EspressoWebDriver\Matcher\MatcherInterface;
use RuntimeException;

final readonly class RetryingMatchProcessor implements MatchProcessorInterface
{
    public function __construct(
        private int $waitTimeoutInSeconds = 5,
        private int $waitIntervalInMilliseconds = 250,
        private MatchProcessorInterface $matchProcessor = new MatchProcessor,
    ) {
        //
    }

    public function process(
        MatcherInterface $target,
        MatcherInterface|MatchResult|null $container,
        EspressoContext $context,
        MatchProcessorOptions $options = new MatchProcessorOptions,
    ): MatchResult {
        $startTime = (float) microtime(true);
        $endTime = $startTime + $this->waitTimeoutInSeconds;
        $waitIntervalInMicroseconds = $this->waitIntervalInMilliseconds * 1000;
        $lastResult = null;

        while (microtime(true) < $endTime) {
            $lastResult = $this->matchProcessor->process($target, $container, $context, $options);

            $count = $lastResult->count();

            $isExpectedCount = match ($options->expectedCount) {
                MatchProcessorExpectedCount::Any => $count > 0,
                MatchProcessorExpectedCount::Many => $count > 1,
                MatchProcessorExpectedCount::None => $count === 0,
                MatchProcessorExpectedCount::Single => $count === 1,
            };

            if ($isExpectedCount) {
                break;
            }

            usleep($waitIntervalInMicroseconds);
        }

        if ($lastResult === null) {
            throw new RuntimeException('No result processed. Ensure the wait time is greater than 0.');
        }

        return $lastResult;
    }
}
