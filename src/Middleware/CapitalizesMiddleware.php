<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

final class CapitalizesMiddleware implements Middleware
{
    public function format(string $date, string $strategy, callable $next): string
    {
        return $next(mb_strtoupper(mb_substr($date, 0, 1)) . mb_substr($date, 1), $strategy);
    }
}