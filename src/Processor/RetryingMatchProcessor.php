<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Matcher\MatcherInterface;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Throwable;

final readonly class RetryingMatchProcessor implements MatchProcessorInterface
{
    private RetryingProcessor $retryProcessor;

    /**
     * @param  class-string[]  $retryableExceptions
     */
    public function __construct(
        int $waitTimeoutInMilliseconds = 5000,
        int $waitIntervalInMilliseconds = 200,
        array $retryableExceptions = [
            StaleElementReferenceException::class,
        ],
        private MatchProcessorInterface $matchProcessor = new MatchProcessor,
    ) {
        $this->retryProcessor = new RetryingProcessor(
            waitTimeoutInMilliseconds: $waitTimeoutInMilliseconds,
            waitIntervalInMilliseconds: $waitIntervalInMilliseconds,
            retryableExceptions: $retryableExceptions,
        );
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
        /**
         * @var MatchResult
         */
        return $this->retryProcessor->process(
            fn () => $this->matchProcessor->process($target, $container, $context, $expectedCount),
        );
    }
}
