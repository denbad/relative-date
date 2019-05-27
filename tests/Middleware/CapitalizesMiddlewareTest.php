<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Middleware;

use Denbad\RelativeDate\Middleware\CapitalizesMiddleware;

final class CapitalizesMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    use GetsNextCallable;

    public function testFormat(): void
    {
        $nextA = $this->getNextCallable();
        $nextA
            ->expects($this->once())
            ->method('__invoke')
            ->with('Result', 'strategy')
            ->willReturn('Result')
        ;

        /** @var callable $nextA */

        $middleware = new CapitalizesMiddleware();
        $this->assertEquals('Result', $middleware->format('result', 'strategy', $nextA));
    }
}