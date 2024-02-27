<?php

declare(strict_types=1);

namespace EspressoWebDriver\Tests\Traits;

use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;

trait MocksWebDriverElement
{
    private static int $mockedElements = 0;

    /**
     * @param  array<int, WebDriverElement&MockObject>  ...$children
     *
     * @throws Exception
     */
    protected function createMockWebDriverElement(
        string $tagName,
        array $attributes = [],
        ?array $children = null,
    ): WebDriverElement&MockObject {
        $element = $this->createMock(WebDriverElement::class);
        $element->expects($this->any())
            ->method('getTagName')
            ->willReturn($tagName);

        $element->expects($this->any())
            ->method('getID')
            ->willReturn('test-'.$tagName.'-'.self::$mockedElements++);

        $element->expects($this->any())
            ->method('getAttribute')
            ->willReturnMap(array_map(
                fn ($key, $value) => [$key, $value],
                array_keys($attributes),
                $attributes,
            ));

        if ($children !== null) {
            if (count($children)) {
                $element->expects($this->any())
                    ->method('findElements')
                    ->willReturn($children);
            } else {
                $element->expects($this->any())
                    ->method('findElements')
                    ->willReturn([]);
            }

            foreach ($children as $child) {
                /** @var MockObject $child */
                $child->expects($this->any())
                    ->method('findElement')
                    ->willReturn($element);
            }
        }

        return $element;
    }
}
