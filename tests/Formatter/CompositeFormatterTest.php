<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Formatter;

use Denbad\RelativeDate\Strategy\Strategy;
use Denbad\RelativeDate\Formatter\CompositeFormatter;
use Denbad\RelativeDate\Middleware\Middleware;
use PHPUnit\Framework\MockObject\MockObject;

final class CompositeFormatterTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat(): void
    {
        /** @var Middleware $middlewareA */
        /** @var Middleware $middlewareB */
        /** @var Middleware $middlewareC */

        $date = new \DateTimeImmutable();
        $dateString = $date->format('c');
        $strategy = Strategy::STRATEGY_RELATIVE_DATETIME;

        $nextToResult = static function (string $carry): string {
            return sprintf('%s -> C', $carry);
        };
        $middlewareC = $this->getMiddleware();
        $middlewareC
            ->expects($this->once())
            ->method('format')
            ->with(sprintf('%s -> A -> B', $dateString), $strategy, $nextToResult)
            ->willReturnCallback($nextToResult)
        ;

        $nextToC = static function (string $carry, string $strategy) use ($middlewareC, $nextToResult): string {
            return $middlewareC->format(sprintf('%s -> B', $carry), $strategy, $nextToResult);
        };
        $middlewareB = $this->getMiddleware();
        $middlewareB
            ->expects($this->once())
            ->method('format')
            ->with(sprintf('%s -> A', $dateString), $strategy, $nextToC)
            ->willReturnCallback($nextToC)
        ;

        $nextToB = static function (string $carry, string $strategy) use ($middlewareB, $nextToC): string {
            return $middlewareB->format(sprintf('%s -> A', $carry), $strategy, $nextToC);
        };
        $middlewareA = $this->getMiddleware();
        $middlewareA
            ->expects($this->once())
            ->method('format')
            ->with($dateString, $strategy, $nextToB)
            ->willReturnCallback($nextToB)
        ;

        $formatter = new CompositeFormatter([$middlewareA, $middlewareB, $middlewareC]);
        $this->assertEquals(sprintf('%s -> A -> B -> C', $dateString), $formatter->format($date));
    }

    private function getMiddleware(): MockObject
    {
        return $this->getMockForAbstractClass(Middleware::class);
    }
}
