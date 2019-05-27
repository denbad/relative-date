<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Middleware;

use Denbad\RelativeDate\Middleware\CachesMiddleware;

final class CachesMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    use GetsNextCallable;

    public function testFormat(): void
    {
        $next = $this->getNextCallable();
        $next
            ->expects($this->at(0))
            ->method('__invoke')
            ->with( 'today', 'strategy-a')
            ->willReturn('today at')
        ;
        $next
            ->expects($this->at(1))
            ->method('__invoke')
            ->with( 'today', 'strategy-b')
            ->willReturn('today at noon')
        ;
        $next
            ->expects($this->at(2))
            ->method('__invoke')
            ->with('yesterday', 'strategy-a')
            ->willReturn('yesterday at')
        ;
        $next
            ->expects($this->at(3))
            ->method('__invoke')
            ->with('yesterday', 'strategy-b')
            ->willReturn('yesterday at noon')
        ;

        $middleware = new CachesMiddleware();

        /** @var callable $next */

        $this->assertEquals('today at', $middleware->format('today', 'strategy-a', $next));
        $this->assertEquals('today at', $middleware->format('today', 'strategy-a', $next));

        $this->assertEquals('today at noon', $middleware->format('today', 'strategy-b', $next));
        $this->assertEquals('today at noon', $middleware->format('today', 'strategy-b', $next));

        $this->assertEquals('yesterday at', $middleware->format('yesterday', 'strategy-a', $next));
        $this->assertEquals('yesterday at', $middleware->format('yesterday', 'strategy-a', $next));

        $this->assertEquals('yesterday at noon', $middleware->format('yesterday', 'strategy-b', $next));
        $this->assertEquals('yesterday at noon', $middleware->format('yesterday', 'strategy-b', $next));

        $this->assertEquals('yesterday at noon', $middleware->format('yesterday', 'strategy-b', $next));
        $this->assertEquals('yesterday at', $middleware->format('yesterday', 'strategy-a', $next));
        $this->assertEquals('today at noon', $middleware->format('today', 'strategy-b', $next));
        $this->assertEquals('today at', $middleware->format('today', 'strategy-a', $next));
    }
}