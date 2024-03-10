<?php

declare(strict_types=1);

namespace EspressoWebDriver\Action;

use EspressoWebDriver\Core\EspressoContext;
use EspressoWebDriver\Exception\AmbiguousElementException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Exception\NoRootElementException;
use EspressoWebDriver\Exception\PerformException;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Processor\MatchProcessorExpectedCount;
use EspressoWebDriver\Processor\MatchProcessorOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverElement;

final readonly class DragAndDropAction implements ActionInterface
{
    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    /**
     * @throws AmbiguousElementException|NoMatchingElementException|NoRootElementException|PerformException
     */
    public function perform(WebDriverElement $target, ?MatcherInterface $container, EspressoContext $context): bool
    {
        if (!($context->driver instanceof RemoteWebDriver)) {
            throw new PerformException(
                action: $this,
                element: $context->options->elementLogger->describe($target),
                reason: 'driver does not have access to input devices',
            );
        }

        $dragElement = $target;
        $dropElement = $context->options->matchProcessor->process(
            target: $this->matcher,
            container: $container,
            context: $context,
            options: new MatchProcessorOptions(
                expectedCount: MatchProcessorExpectedCount::Single,
            ));

        $script = file_get_contents(dirname(__DIR__, 2).'/resources/dist/drag-and-drop.js');
        if (!$script) {
            throw new PerformException(
                action: $this,
                element: $context->options->elementLogger->describe($target),
                reason: 'unable to find drag and drop helper script',
            );
        }

        $dragAndDrop = trim($script);

        $script = sprintf('(%1$s)(arguments[0], arguments[1]);', $dragAndDrop);

        $context->driver->executeScript($script, [$dragElement, $dropElement->single()]);

        return true;
    }

    public function __toString(): string
    {
        return sprintf('dragAndDrop(%1$s)', $this->matcher);
    }
}
