<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Tests\Middleware;

use Denbad\RelativeDate\Middleware\Translator;
use Denbad\RelativeDate\Middleware\TranslatesMiddleware;
use PHPUnit\Framework\MockObject\MockObject;

final class TranslatesMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    use GetsNextCallable;

    public function testFormat(): void
    {
        /** @var Translator $translator */

        $date = 'today at 06:00 pm';
        $result = 'сегодня в 06:00 вечера';
        $strategy = 'strategy';

        $translator = $this->getTranslator();
        $translator
            ->expects($this->at(0))
            ->method('trans')
            ->with('today')
            ->willReturn('сегодня')
        ;
        $translator
            ->expects($this->at(1))
            ->method('trans')
            ->with('at')
            ->willReturn('в')
        ;
        $translator
            ->expects($this->at(2))
            ->method('trans')
            ->with('06:00')
            ->willReturn('06:00')
        ;
        $translator
            ->expects($this->at(3))
            ->method('trans')
            ->with('pm')
            ->willReturn('вечера')
        ;

        $next = $this->getNextCallable();
        $next
            ->expects($this->once())
            ->method('__invoke')
            ->with($result, $strategy)
            ->willReturn($result)
        ;

        $middleware = new TranslatesMiddleware($translator);
        $this->assertEquals($result, $middleware->format($date, $strategy, $next));
    }

    private function getTranslator(): MockObject
    {
        return $this->getMockForAbstractClass(Translator::class);
    }
}
