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

        // 3 Middleware
        $nextToResult = static function (string $carry): string {
            return sprintf('%s -> C', $carry);
        };
        $middlewareC = $this->getMiddleware(sprintf('%s -> A -> B', $dateString), $strategy, $nextToResult);

        // 2 Middleware
        $nextToC = static function (string $carry, string $strategy) use ($middlewareC, $nextToResult): string {
            return $middlewareC->format(sprintf('%s -> B', $carry), $strategy, $nextToResult);
        };
        $middlewareB = $this->getMiddleware(sprintf('%s -> A', $dateString), $strategy, $nextToC);

        // 1 Middleware
        $nextToB = static function (string $carry, string $strategy) use ($middlewareB, $nextToC): string {
            return $middlewareB->format(sprintf('%s -> A', $carry), $strategy, $nextToC);
        };
        $middlewareA = $this->getMiddleware($dateString, $strategy, $nextToB);

        $formatter = new CompositeFormatter([$middlewareA, $middlewareB, $middlewareC]);
        $this->assertEquals(sprintf('%s -> A -> B -> C', $dateString), $formatter->format($date));
    }

    private function getMiddleware(string $date, string $strategy, callable $next): MockObject
    {
        $mock = $this->getMockForAbstractClass(Middleware::class);

        $mock
            ->expects($this->once())
            ->method('format')
            ->with($date, $strategy, $next)
            ->willReturnCallback($next)
        ;

        return $mock;
    }
}
