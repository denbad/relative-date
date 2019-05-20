<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Formatter;

use Denbad\RelativeDate\Middleware\Middleware;

final class CompositeFormatter implements Formatter
{
    /** @var Middleware[] */
    private $middlewares = [];

    public function __construct(iterable $middlewares = [])
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = $middleware;
        }
    }

    public function format(\DateTimeImmutable $date, string $format): string
    {
        return call_user_func($this->callableForNextMiddleware(0), (clone $date)->format('c'), $format);
    }

    private function callableForNextMiddleware(int $index): callable
    {
        if (!isset($this->middlewares[$index])) {
            return static function (string $date): string {
                return $date;
            };
        }

        $middleware = $this->middlewares[$index];

        return function (string $date, string $format) use ($middleware, $index): string {
            return $middleware->format($date, $format, $this->callableForNextMiddleware($index + 1));
        };
    }
}