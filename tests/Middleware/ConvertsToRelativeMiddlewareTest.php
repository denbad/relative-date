<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Middleware;

use Denbad\RelativeDate\Strategy\Strategy;
use Denbad\RelativeDate\Middleware\ConvertsToRelativeMiddleware;
use PHPUnit\Framework\MockObject\MockObject;

final class ConvertsToRelativeMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    use GetsNextCallable;

    public function testFormat(): void
    {
        /** @var Strategy $strategy */
        /** @var callable $next */
        /** @var callable $next404 */

        $date = 'initial';

        $strategy = $this->getStrategy();
        $strategy
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('strategy')
        ;
        $strategy
            ->expects($this->once())
            ->method('__invoke')
            ->with($date)
            ->willReturn('result')
        ;

        $next = $this->getNextCallable();
        $next
            ->expects($this->once())
            ->method('__invoke')
            ->with('result', 'strategy')
            ->willReturn('result-result')
        ;

        $next404 = $this->getNextCallable();
        $next404
            ->expects($this->once())
            ->method('__invoke')
            ->with('', '404')
            ->willReturn('')
        ;

        $middleware = new ConvertsToRelativeMiddleware([$strategy]);

        $this->assertEquals('result-result', $middleware->format($date, 'strategy', $next));
        $this->assertEquals('', $middleware->format($date, '404', $next404));

    }

    private function getStrategy(): MockObject
    {
        return $this->getMockForAbstractClass(Strategy::class);
    }
}
