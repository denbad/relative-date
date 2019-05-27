<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

final class CachesMiddleware implements Middleware
{
    private $cache = [];

    public function format(string $date, string $strategy, callable $next): string
    {
        $key = $strategy . ':' . $date;

        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        $this->cache[$key] = $next($date, $strategy);

        return $this->cache[$key];
    }
}