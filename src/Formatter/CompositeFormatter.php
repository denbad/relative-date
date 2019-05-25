<?php

declare(strict_types=1);

namespace Denbad\RelativeDate\Formatter;

use Denbad\RelativeDate\Middleware\Middleware;

final class CompositeFormatter implements Formatter
{
    /** @var Middleware[] */
    private $middlewares = [];
    private $defaultFormat;

    public function __construct(iterable $middlewares = [], string $defaultFormat = 'relative-date')
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = $middleware;
        }

        $this->defaultFormat = $defaultFormat;
    }

    public function format(\DateTimeImmutable $date, string $format = null): string
    {
        return call_user_func(
            $this->callableForNextMiddleware(0),
            (clone $date)->format('c'),
            $format ?? $this->defaultFormat
        );
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