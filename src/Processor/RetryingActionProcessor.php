<?php

declare(strict_types=1);

namespace EspressoWebDriver\Processor;

use EspressoWebDriver\Action\ActionInterface;
use EspressoWebDriver\Core\EspressoContext;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\Exception\ElementNotInteractableException;
use Throwable;

/**
 * When performing actions like click, if the element is in an animation state WebDriver might throw an exception.
 * This processor will retry the action the specified number of times in order to give the animation time to complete.
 *
 *  - While animating in from under another element, the click will throw ElementClickInterceptedException.
 *  - While animating in from outside the viewport, the click will throw ElementNotInteractableException.
 */
final readonly class RetryingActionProcessor implements ActionProcessorInterface
{
    /**
     * Most WebDriver actions take some time, so we don't need many attempts or much delay to allow for most animations
     * to complete.
     *
     * @param  class-string[]  $retryableExceptions
     */
    public function __construct(
        private int $retryAttempts = 1,
        private int $retryDelayInMilliseconds = 100,
        private array $retryableExceptions = [
            ElementClickInterceptedException::class,
            ElementNotInteractableException::class,
        ],
        private ActionProcessorInterface $actionProcessor = new ActionProcessor,
    ) {
        //
    }

    /**
     * @throws Throwable
     */
    public function process(
        ActionInterface $action,
        MatchResult $target,
        EspressoContext $context,
    ): bool {
        $retries = 0;
        $retryDelayInMicroseconds = $this->retryDelayInMilliseconds * 1000;

        $lastException = null;

        while ($retries <= $this->retryAttempts) {
            try {
                $lastException = null;

                return $this->actionProcessor->process($action, $target, $context);
            } catch (Throwable $e) {
                if (!in_array(get_class($e), $this->retryableExceptions, true)) {
                    throw $e;
                }

                $lastException = $e;
            }

            $retries++;

            if ($retries > $this->retryAttempts) {
                break;
            }

            usleep($retryDelayInMicroseconds);
        }

        if ($lastException !== null) {
            throw $lastException;
        }

        return false;
    }
}
