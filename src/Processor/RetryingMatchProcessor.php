<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use RuntimeException;
use Throwable;

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
        ExpectedMatchCount $expectedCount,
    ): MatchResult {
        $startTime = (float) microtime(true);
        $endTime = $startTime + $this->waitTimeoutInSeconds;
        $waitIntervalInMicroseconds = $this->waitIntervalInMilliseconds * 1000;
        $lastResult = null;
        $lastException = null;

        while (microtime(true) < $endTime) {
            try {
                $lastException = null;
                $lastResult = $this->matchProcessor->process($target, $container, $context, $expectedCount);
            } catch (Throwable $exception) {
                // TODO Should we narrow this and only retry some exceptions?
                //      StaleElementReferenceException for example
                $lastException = $exception;

                continue;
            }

            $count = $lastResult->count();

            $isExpectedCount = match ($expectedCount) {
                ExpectedMatchCount::OneOrMore => $count > 0,
                ExpectedMatchCount::TwoOrMore => $count > 1,
                ExpectedMatchCount::Zero => $count === 0,
                ExpectedMatchCount::One => $count === 1,
            };

            if ($isExpectedCount) {
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
