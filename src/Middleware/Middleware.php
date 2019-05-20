<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Middleware;

interface Middleware
{
    public function format(string $date, string $format, callable $next): string;
}
