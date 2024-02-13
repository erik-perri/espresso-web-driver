<?php

declare(strict_types=1);

namespace EspressoWebDriver\Matcher;

use EspressoWebDriver\Exception\AmbiguousElementMatcherException;
use EspressoWebDriver\Exception\NoMatchingElementException;
use EspressoWebDriver\Traits\HasAutomaticWait;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

final readonly class HasDescendantMatcher implements MatcherInterface
{
    use HasAutomaticWait;

    public function __construct(private MatcherInterface $matcher)
    {
        //
    }

    public function match(MatchResult $container, MatchContext $context): MatchResult
    {
        return $this->waitForMatch($context, fn () => $this->matchElements($container, $context));
    }

    /**
     * @return WebDriverElement[]
     *
     * @throws AmbiguousElementMatcherException|NoMatchingElementException
     */
    private function matchElements(MatchResult $container, MatchContext $context): array
    {
        $childContext = new MatchContext(
            driver: $context->driver,
            isNegated: $context->isNegated,
            // Since we are waiting ourselves, we don't want the child matchers to wait as well.
            options: $context->options->toInstantOptions(),
        );

        $descendantMatch = $this->matcher->match($container, $childContext);

        $elements = [];

        foreach ($descendantMatch->all() as $descendant) {
            /** @var WebDriverElement[] $ancestors */
            $ancestors = array_reverse($descendant->findElements(WebDriverBy::xpath('./ancestor::*')));

            if (!count($ancestors)) {
                continue;
            }

            foreach ($ancestors as $ancestor) {
                if ($context->isNegated) {
                    // Since something like withText will include parents that we don't want to include when negated
                    // we need to check again without the negation.
                    $ancestorMatch = $this->matcher->match(new MatchResult($this->matcher, [$ancestor]), new MatchContext(
                        driver: $context->driver,
                        isNegated: false,
                        options: $context->options,
                    ));

                    if ($ancestorMatch->count()) {
                        continue;
                    }
                }

                $elements[] = $ancestor;
            }
        }

        return $elements;
    }

    public function __toString(): string
    {
        return sprintf('descendant(%1$s)', $this->matcher);
    }
}
