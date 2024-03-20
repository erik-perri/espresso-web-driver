<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\Exception\ElementNotInteractableException;

final readonly class RetryingActionProcessor implements ActionProcessorInterface
{
    private RetryingProcessor $retryProcessor;

    /**
     * @param  class-string[]  $retryableExceptions
     */
    public function __construct(
        int $waitTimeoutInMilliseconds = 500,
        int $waitIntervalInMilliseconds = 100,
        array $retryableExceptions = [
            ElementClickInterceptedException::class,
            ElementNotInteractableException::class,
        ],
        private ActionProcessorInterface $actionProcessor = new ActionProcessor,
    ) {
        $this->retryProcessor = new RetryingProcessor(
            waitTimeoutInMilliseconds: $waitTimeoutInMilliseconds,
            waitIntervalInMilliseconds: $waitIntervalInMilliseconds,
            retryableExceptions: $retryableExceptions,
        );
    }

    public function process(
        ActionInterface $action,
        MatchResult $target,
        EspressoContext $context,
    ): ActionResult {
        /**
         * @var ActionResult
         */
        return $this->retryProcessor->process(
            fn () => $this->actionProcessor->process($action, $target, $context),
        );
    }
}
