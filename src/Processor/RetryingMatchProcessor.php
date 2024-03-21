<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use RuntimeException;
use Throwable;

/**
 * When performing a match if an element is not found, or more elements than expected are found, this processor will
 * retry the match until the expected result is found or the timeout is reached.
 *
 * It will also retry on StaleElementReferenceException to handle cases where the element that was found is no longer
 * in the DOM and needs to be re-matched.
 */
final readonly class RetryingMatchProcessor implements MatchProcessorInterface
{
    /**
     * @param  class-string[]  $retryableExceptions
     */
    public function __construct(
        private int $waitTimeoutInMilliseconds = 4000,
        private int $waitIntervalInMilliseconds = 200,
        private array $retryableExceptions = [
            StaleElementReferenceException::class,
        ],
        private MatchProcessorInterface $matchProcessor = new MatchProcessor,
    ) {
        //
    }

    /**
     * @throws Throwable
     */
    public function process(
        MatcherInterface $target,
        MatcherInterface|MatchResult|null $container,
        EspressoContext $context,
        ExpectedMatchCount $expectedCount,
    ): MatchResult {
        $startTime = (float) microtime(true);
        $endTime = $startTime + $this->waitTimeoutInMilliseconds / 1000;
        $waitIntervalInMicroseconds = $this->waitIntervalInMilliseconds * 1000;

        $lastResult = null;
        $lastException = null;

        while (microtime(true) < $endTime) {
            try {
                $lastException = null;
                $lastResult = $this->matchProcessor->process($target, $container, $context, $expectedCount);

                if (!$lastResult->shouldRetry()) {
                    break;
                }
            } catch (Throwable $exception) {
                if (!in_array(get_class($exception), $this->retryableExceptions, true)) {
                    throw $exception;
                }

                $lastException = $exception;
            }

            if (microtime(true) >= $endTime) {
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
