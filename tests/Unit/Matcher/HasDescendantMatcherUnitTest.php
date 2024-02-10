<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Unit\Matcher;

use EspressoWebDriver\Matcher\HasDescendantMatcher;
use EspressoWebDriver\Matcher\MatcherInterface;
use EspressoWebDriver\Tests\Unit\BaseUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasDescendantMatcher::class)]
class HasDescendantMatcherUnitTest extends BaseUnitTestCase
{
    public function testHasDescendantToString(): void
    {
        $mockMatcher = $this->createMock(MatcherInterface::class);

        $mockMatcher
            ->method('__toString')
            ->willReturn('mock="test"');

        $matcher = new HasDescendantMatcher($mockMatcher);

        $this->assertSame('descendant(mock="test")', (string) $matcher);
    }
}
